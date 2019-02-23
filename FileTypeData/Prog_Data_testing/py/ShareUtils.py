import urllib2
import pandas as pd
from bs4 import BeautifulSoup
import re
from SequenceUtils import *
import json

def getSharesInfo(url, fetchCount):
    page = urllib2.urlopen(url)
    soup = BeautifulSoup(page, "lxml")
    table = soup.find('table', class_='dataTable')

    # Generate lists
    scripName = []
    prevPrice = []
    curPrice = []
    change = []

    count = 0
    for row in table.findAll("tr"):
        cells = row.findAll('td')
        if len(cells) == 5:  # Only extract table body not heading
            count = count + 1
            stock = cells[0].find("a").string
            stock = re.sub('[^A-Za-z0-9]+', '', stock)
            scripName.append(stock)
            prevPrice.append(cells[2].find(text=True))
            curPrice.append(cells[3].find(text=True))
            change.append(cells[4].find(text=True))

        if count == 500:
            break

    df = pd.DataFrame(scripName, columns=['Stock'])
    df['Prev'] = prevPrice
    df['Current'] = curPrice
    df['Change'] = change
    # seqNo = next(fetchCounter)
    seqNo = fetchCount
    df['Seq'] = [seqNo] * len(scripName)
    return df


def getGainersTableFromURL():
    gainers = "http://money.rediff.com/gainers/bse/daily/groupa?src=gain_lose"
    page = urllib2.urlopen(gainers)
    soup = BeautifulSoup(page, "lxml")
    gainersTable = soup.find('table', class_='dataTable')
    return gainersTable

def getGainersDFFromTable(table):
    global counter
    # Generate lists
    scripName = []
    prevPrice = []
    curPrice = []
    change = []

    count = 0
    for row in table.findAll("tr"):
        cells = row.findAll('td')
        if len(cells) == 5:  # Only extract table body not heading
            count = count + 1
            stock = cells[0].find("a").string
            stock = re.sub('[^A-Za-z0-9]+', '', stock)
            scripName.append(stock)
            prevPrice.append(cells[2].find(text=True))
            curPrice.append(cells[3].find(text=True))
            change.append(cells[4].find(text=True))

        if count == 500:
            break

    df = pd.DataFrame(scripName, columns=['Stock'])
    df['Prev'] = prevPrice
    df['Current'] = curPrice
    df['Change'] = change
    seqNo = next(counter)
    df['Seq'] = [seqNo] * len(scripName)
    return df


def getLatestGainersDF():
    # print "Getting Data"
    scripTable = getGainersTableFromURL()
    df = getGainersDFFromTable(scripTable)

    # print que[-1]["Stock"].iloc[0]
    return df

def getTotalBuySellVolumes(scrip):
    # print "START Scrip ", scrip

    scrip = re.sub('[&]+', '%26', scrip)

    fnoURL = "https://nseindia.com/live_market/dynaContent/live_watch/get_quote/GetQuote.jsp?symbol=" + scrip

    r = urllib2.Request(fnoURL)
    r.add_header('Accept', '*/*')
    r.add_header('User-Agent', 'Quotes')
    opener = urllib2.build_opener()
    page = opener.open(r).read()

    # page = urllib2.urlopen(fnoData)
    #html5lib
    soup = BeautifulSoup(page, "lxml")
    val = soup.find(id="responseDiv").text

    j = json.loads(val)

    totalBuyQuantity = extractNumber(j, 'totalBuyQuantity')
    totalSellQuantity = extractNumber(j, 'totalSellQuantity')
    dayHigh = extractNumber(j, 'dayHigh')
    dayLow= extractNumber(j, 'dayLow')
    previousClose = extractNumber(j, 'previousClose')
    latestPrice = extractNumber(j, 'lastPrice')
    pctChange = extractNumber(j, 'pChange')
    change = extractNumber(j, 'change')

    # totalBuyQuantity = j['data'][0]['totalBuyQuantity']
    # totalBuyQuantity = getfloat(totalBuyQuantity.replace(',', ''))

    # totalSellQuantity = j['data'][0]['totalSellQuantity']
    # totalSellQuantity = getfloat(totalSellQuantity.replace(',', ''))
    #
    # dayHigh = j['data'][0]['dayHigh']
    # dayHigh = getfloat(dayHigh.replace(',', ''))
    #
    # dayLow = j['data'][0]['dayLow']
    # dayLow = getfloat(dayLow.replace(',', ''))
    #
    # previousClose = j['data'][0]['previousClose']
    # previousClose = getfloat(previousClose.replace(',', ''))


    # print "END Scrip ", scrip

    return totalBuyQuantity, totalSellQuantity, dayHigh, dayLow, previousClose, latestPrice, pctChange, change


def  extractNumber(j, fieldName):

    value = j['data'][0][fieldName]
    value= getfloat(value.replace(',', ''))

    return value

def getfloat(value):
    value = str(value).replace(',', '')
    try:
        val = float(value)
        return val
    except:
        return 0.0