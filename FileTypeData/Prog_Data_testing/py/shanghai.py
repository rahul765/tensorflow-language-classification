# -*- coding: utf-8 -*-
import scrapy
import re
from ..items import ShanghaiItem


class ShanghaiSpider(scrapy.Spider):
    name = "shanghai"
    allowed_domains = ["www.datashanghai.gov.cn"]
    start_urls = (
        'http://www.datashanghai.gov.cn/home!toHomePage.action',
    )
    item = ShanghaiItem()
    count = 0

    def parse(self, response):
        cateURLs = response.xpath(
            '//*[@id="dataClass"]/ul/li/a/@href').extract()
        for cateURL in cateURLs:
            url = response.urljoin(cateURL)
            yield scrapy.Request(url, callback=self.parse_dir_contents, meta={'item': url})

    def parse_dir_contents(self, response):
        url = response.meta['item']
        category = self.noSpace(
            response, '//*[@id="side"]/div[1]/p/span/text()')
        contentURLs = response.xpath(
            '//*[@id="side"]/div[2]/dl/dd/a/@href').extract()
        for i in xrange(1, len(contentURLs)):
            contentURL = response.urljoin(contentURLs[i])
            data_type = re.findall(r"dataType=(\d)&", contentURL)[0]
            nums = re.findall(r'(\d+)',
                              response.xpath('//*[@id="side"]/div[2]/dl/dd[' + str(i + 1) + ']/a/text()').extract()[0])
            if len(nums) > 0:
                nums = int(nums[0])
                if ((nums - nums / 10 * 10) != 0):
                    totalPages = nums / 10 + 1
                else:
                    totalPages = nums / 10
            else:
                totalPages = 0
            item = {}
            item['url'] = url
            item['category'] = category
            item['data_type'] = data_type
            item['totalPages'] = totalPages
            request = scrapy.Request(contentURL,
                                     callback=self.content_pages,
                                     meta={'item': item})
            yield request

    def noSpace(self, response, xpath):
        if len(response.xpath(xpath).extract()) > 0:
            result = re.findall(r'\S+',
                                response.xpath(xpath).extract()[0])[0]
        else:
            result = ''
        return result

    def content_pages(self, response):
        item = response.meta['item']
        data_type = item['data_type']
        totalPages = item['totalPages']
        baseURL = item['url']
        for i in xrange(1, totalPages + 1):
            pageURL = baseURL + '&dataType=' + \
                data_type + '&currentPage=' + str(i)
            category = item['category']
            yield scrapy.Request(pageURL,
                                 callback=self.data_pages,
                                 meta={'item': category})

    def data_pages(self, response):
        category = response.meta['item']
        type_3 = "query!queryGdsInterfaceInfoById.action?dataId="
        tyep_4 = "query!queryMobileAppById.action?dataId="
        type_0 = "query!queryGdsDataInfoById.action?type="
        for i in range(len(response.xpath('//*[@id="content"]/dl/dt'))):
            sel = response.xpath('//*[@id="content"]/dl/dt[' +
                                 str(i + 1) + ']/a/@href').extract()[0]
            data_id = re.findall(r"'(.*?)',", sel)[0]
            data_type = re.findall(r",'(.*?)'", sel)[0]
            if int(data_type) == 3:
                href = type_3 + data_id
            elif int(data_type) == 4:
                href = tyep_4 + data_id
            else:
                href = type_0 + data_type + "&dataId=" + data_id
            url = response.urljoin(href)
            update = response.xpath(
                '//*[@id="content"]/dl/dd[' + str(i + 1) +
                ']/span/text()').extract()[0]
            item = {}
            item['data_id'] = data_id
            item['data_type'] = data_type
            item['updated_date'] = '-'.join(re.findall(r'\d+', update))
            item['category'] = category
            item['url'] = url
            yield scrapy.Request(url,
                                 callback=self.parse_articles_follow_next_page,
                                 meta={'item': item})

    def parse_articles_follow_next_page(self, response):
        temp = response.meta['item']
        self.item['data_id'] = temp['data_id']
        self.data_type = temp['data_type']
        self.item['title'] = self.noSpace(response,
                                          '//*[@id="wrap"]/div/h2/text()')
        self.item['updated_date'] = temp['updated_date']
        self.item['category'] = temp['category']
        if int(self.data_type) == 3:
            self.item['descr'] = self.noSpace(response,
                                              '//*[@id="wrap"]/div[1]/table[1]/tbody/tr[2]/td/text()')
            self.item['tag'] = self.noSpace(
                response, '//*[@id="wrap"]/div[1]/table[1]/tbody/tr[3]/td/text()')
            self.item['orgnization'] = self.noSpace(
                response, '//*[@id="wrap"]/div[1]/table[1]/tbody/tr[8]/td/text()')
            self.item['num_of_download'] = re.findall(r'\S+', response.xpath(
                '//*[@id="wrap"]/div[1]/table[1]/tbody/tr[1]/td/text()').extract()[0])[-1]
            self.item['num_of_visits'] = re.findall(r'\S+', response.xpath(
                '//*[@id="wrap"]/div[1]/table[1]/tbody/tr[1]/td/text()').extract()[0])[0]
            self.item['format'] = self.noSpace(response,
                                               '//*[@id="wrap"]/div[1]/table[2]/tbody/tr[6]/td/em/a/span/text()')
            self.item['publication_date'] = ''
            self.item['update_frequency'] = ''
            self.item['publication_type'] = self.noSpace(
                response, '//*[@id="wrap"]/div[1]/table[1]/tbody/tr[6]/td/text()')
            self.item['update_on_time'] = ''
            self.item['updated_on_time_value'] = ''
            self.item['num_of_files'] = '1'
        elif int(self.data_type) == 4:
            self.item['descr'] = self.noSpace(
                response, '//*[@id="wrap"]/div/table/tbody/tr[5]/td/text()')
            self.item['num_of_download'] = re.findall(r'\S+', response.xpath(
                '//*[@id="wrap"]/div/table/tbody/tr[1]/td/text()').extract()[0])[-1]
            self.item['num_of_visits'] = re.findall(r'\S+', response.xpath(
                '//*[@id="wrap"]/div/table/tbody/tr[1]/td/text()').extract()[0])[0]
            self.item['format'] = self.noSpace(
                response, '//*[@id="s2"]/text()')
            temp = self.noSpace(
                response, '//*[@id="wrap"]/div/table/tbody/tr[2]/td/text()')
            self.item['publication_date'] = '-'.join(re.findall('\d+', temp))
            self.item['publication_type'] = "普通公开"
            self.item['update_on_time'] = ''
            self.item['updated_on_time_value'] = ''
            self.item['num_of_files'] = '1'
        else:
            self.item['descr'] = self.noSpace(
                response, '//*[@id="wrap"]/div/table/tbody/tr[2]/td/text()')
            self.item['tag'] = self.noSpace(
                response, '//*[@id="wrap"]/div/table/tbody/tr[3]/td/text()')
            self.item['orgnization'] = self.noSpace(
                response, '//*[@id="wrap"]/div/table/tbody/tr[11]/td/text()')
            self.item['num_of_download'] = re.findall(r'\S+', response.xpath(
                '//*[@id="wrap"]/div/table/tbody/tr[1]/td/text()').extract()[0])[-1]
            self.item['num_of_visits'] = re.findall(r'\S+', response.xpath(
                '//*[@id="wrap"]/div/table/tbody/tr[1]/td/text()').extract()[0])[0]
            self.item['format'] = self.noSpace(
                response, '//*[@id="wrap"]/div/table/tbody/tr[13]/td/em/a/span/text()')
            self.item['publication_date'] = self.noSpace(
                response, '//*[@id="wrap"]/div/table/tbody/tr[9]/td/text()')
            self.item['update_frequency'] = self.noSpace(
                response, '//*[@id="wrap"]/div/table/tbody/tr[8]/td/text()')
            self.item['publication_type'] = self.noSpace(
                response, '//*[@id="wrap"]/div/table/tbody/tr[7]/td/text()')
            self.item['update_on_time'] = ''
            self.item['updated_on_time_value'] = ''
            self.item['num_of_files'] = '1'
        self.item['is_api_data'] = 1 if (int(self.data_type) == 3) else 0
        self.count += 1
        print str(self.count)
        yield self.item
