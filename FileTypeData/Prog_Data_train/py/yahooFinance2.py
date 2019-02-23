# -*- coding: utf-8 -*-
"""
Created on Mon Apr 27 01:35:42 2015

@author: Craig
"""

import urllib
 
def yahooFinance(yahooTicker):
    '''get ticker data from yahoo web site'''
    # next line is where to get information about Yahoo parameters
    # http://www.jarloo.com/yahoo_finance/
    # http://www.jarloo.com/get-historical-stock-data/
    #
    url='http://ichart.yahoo.com/table.csv?s=%s&a=0&b=1&c=2010&d=7&e=31&f=2015&g=w&ignore=.csv' % (yahooTicker)
    data=urllib.urlopen(url).read()
    return data
if __name__ == '__main__':
    tickers=["AAPL", "IBM", "ETSY", "T", "TSLA", "GE", "YHOO", "GOOG"]
    
    for ticker in tickers:
        outFile='%s.csv' % (ticker.lower())
        with open(outFile, 'w') as oFile:
            #oFile.write('Ticker,Open,High,Low,52wHigh,52wLow,Last,Volume\n')
            csvData = ('%s') % (yahooFinance(ticker))
            oFile.write(csvData)