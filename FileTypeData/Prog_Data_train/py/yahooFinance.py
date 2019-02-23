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
    # o=Open,h=DayHigh,g=DayLow,k=52wHigh,j=52wLow,l=LastTradeWithTime,v=Volume
    url='http://finance.yahoo.com/d/quotes.csv?s=%s&f=ohgkjlv' % (yahooTicker)
    data=urllib.urlopen(url).read()
    return data[:-1]  # this "[:-1]" strips off the new line
    
if __name__ == '__main__':
    tickers=["AAPL", "IBM", "ETSY", "T", "TSLA", "GE", "YHOO", "GOOG", "SNE"]
    print 'Ticker,Open,High,Low,52wHigh,52wLow,Last,Volume'
    for ticker in tickers:
        print ('%s,%s') % (ticker.upper(), yahooFinance(ticker))
