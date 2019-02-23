__author__ = 'asethi'
from selenium import webdriver
from selenium.webdriver.support.ui import Select

#geckodriver is firefox selenium webdriver and needs to be on the classPath.
browser = webdriver.Firefox(executable_path='C:\Users\sethi\Desktop\selenium-2.47.1\Python Selenium\geckodriver.exe')

url="http://careers.bankofamerica.com/search-jobs.aspx?c=united-states&r=us"
browser.get(url)
browser.implicitly_wait(5)

#Selecting drop down to make 100 jobs per page
#select = Select(browser.find_element_by_class_name('ddl-page'))
#select.select_by_visible_text('100') # select by visible text

CareerAreaElement=browser.find_element_by_id("ui-accordion-1-header-2")
CareerAreaElement.click()
browser.implicitly_wait(3)


select = Select(browser.find_element_by_class_name('jobs-area'))
select.select_by_index(16) # select by visible text="Technology" or 16
browser.implicitly_wait(3)

submitForm=browser.find_element_by_id("PlhContentWrapper_btnSubmit")
submitForm.click()
browser.implicitly_wait(10)

#elem = browser.find_element_by_class_name('tbl-result')  # Find the results table
elem=browser.find_element_by_class_name('tbl-result')
count=0;

for i in elem.find_elements_by_xpath('.//tbody/tr'):
    count = count + 1
    print count, "ROW COUNT^^^^^^^^^^^^^^^^^^^^^^^", i.get_attribute('innerHTML')











