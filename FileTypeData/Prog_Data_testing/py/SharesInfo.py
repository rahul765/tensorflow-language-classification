from collections import deque
from ShareUtils import *
from itertools import count
from InterestedStocks import *
from multiprocessing.dummy import Pool  # This is a thread-based Pool
from multiprocessing import cpu_count
from collections import defaultdict
import math

class SharesInfo:


    def __init__(self, sharesType, historyLength, sourceLink):
        self.type = sharesType
        self.que = deque(maxlen= historyLength)
        self.sourceLink = sourceLink
        self.fetchCounter = count()
        self.intList = loadInterestingStocks()
        self.bucketSMSList = {}

    def filter_shares(self):

        for scrip in self.df['Stock']:
            founddf = self.intList.loc[
                self.intList.FullName.str.contains(scrip) | (self.intList.Symbol == scrip.upper())]
            if (founddf.empty):
                # print "Removing ", scrip
                self.df = self.df[self.df.Stock != scrip]

    def combineResults(self):
        result = pd.concat(list(self.que))
        result.sort_values(['Stock', 'Seq'], ascending=[False, False], inplace=True)
        latestList = self.que[-1].sort_values(['Stock'], ascending=[True], inplace=False)

        return result, latestList['Stock']

    def analyzeResults(self, result, latestList):
        curMax = result['Seq'].max()
        # print curMax
        scrips = []
        latestChange = []
        curPrice = []
        goodChange = []
        for scrip in latestList:
            currentPrice, change, isGood = self.isGoodScrip(scrip, result[result['Stock'] == scrip])
            scrips.append(scrip)
            latestChange.append(change)
            curPrice.append(currentPrice)
            goodChange.append("Y" if (isGood) else "N")

        df = pd.DataFrame(scrips, columns=['Stock'])
        df['Gain'] = latestChange
        df['CurPrice'] = curPrice
        df['goodChange'] = goodChange

        df.sort_values(['Gain'], ascending=[False], inplace=True)

        return df

    def getVolumesInfo(self, scrips):
        pool = Pool(cpu_count() * 10)  # Creates a Pool with cpu_count * 2 threads.

        # for (idx, row) in intDF.iterrows():
        results = pool.map(getTotalBuySellVolumes,
                           scrips)  # results is a list of all the placeHolder lists returned from each call to crawlToCSV

        return results

    def getVolumesForGoodScrips(self, scripsDF):
        scripsDF['totalBuyQuantity'] = ""
        scripsDF['totalSellQuantity'] = ""
        scripsDF['buyIndicator'] = ""
        scripsDF['Symbol'] = ""
        scripsDF['SMSSymbol'] = ""
        scripsDF['dayHigh'] = ""
        scripsDF['threshold'] = ""
        scripsDF['dayLow'] = ""
        scripsDF['previousClose'] = ""

        topTwenty = []

        curIndex = 0
        for (idx, row) in scripsDF.iterrows():
            totalBuyQuantity, totalSellQuantity, dayHigh = 0, 0, 0
            founddf = self.intList.loc[self.intList.FullName.str.contains(row.Stock) | (self.intList.Symbol == row.Stock.upper())]
            scrip = founddf.iloc[0].Symbol
            smsScrip = founddf.iloc[0].SMSSymbol
            threshold = founddf.iloc[0].threshold
            row.Symbol = scrip  # This is not assigned correctly
            row.SMSSymbol = smsScrip
            row.threshold = threshold
            if (curIndex <= 20):
                topTwenty.append(scrip)
            curIndex += 1

        volumesInfo = self.getVolumesInfo(topTwenty)
        curIndex = 0
        for (idx, row) in scripsDF.iterrows():
            if (curIndex <= 20):
                totalBuyQuantity, totalSellQuantity, dayHigh, dayLow, previousClose, latestPrice, pctChange, change = \
                volumesInfo[curIndex]
                row.totalBuyQuantity = totalBuyQuantity
                row.totalSellQuantity = totalSellQuantity
                row.dayHigh = dayHigh
                row.dayLow = dayLow
                row.previousClose = previousClose
                row.curPrice = latestPrice
                row.gain = pctChange
                if self.type == 'Losers' :
                    row.buyIndicator = "NO" if (totalBuyQuantity > totalSellQuantity) else "YES"
                else:
                    row.buyIndicator = "YES" if (totalBuyQuantity > totalSellQuantity) else "NO"
            else:
                break
            curIndex += 1
            #  #Get buy/sell quantity only for top 20 shares.
            # if (curIndex <= 20):
            #     totalBuyQuantity, totalSellQuantity, dayHigh = getTotalBuySellVolumes(scrip)

        return scripsDF

    def getVolumesInfo(self, scrips):
        pool = Pool(cpu_count() * 10)  # Creates a Pool with cpu_count * 2 threads.

        # for (idx, row) in intDF.iterrows():
        results = pool.map(getTotalBuySellVolumes,
                           scrips)  # results is a list of all the placeHolder lists returned from each call to crawlToCSV

        return results

    def isGoodScrip(self, scrip, history):
        latest = history['Change'].iloc[0]
        oldest = history['Change'].iloc[-1]

        latest = latest.lstrip('-').strip()
        latest = latest.lstrip('+').strip()

        oldest = oldest.lstrip('-').strip()
        oldest = oldest.lstrip('+').strip()

        curPrice = history['Current'].iloc[0]
        # print scrip, latest, oldest
        return curPrice, latest , (
        float(latest ) - float(oldest ) >= 0)

    def getSMSText(self, scrips):
        text = ""
        smsText = ""
        smsPerBucket = defaultdict(list)
        curPos = 0
        for (idx, row) in scrips.iterrows():

            curRow = {}

            curRow['symbol'] = row.Symbol
            curRow['curPrice'] = row.CurPrice
            curRow['gain'] = row.Gain
            curRow['buyQuantity'] = row.totalBuyQuantity
            curRow['sellQuantity'] = row.totalSellQuantity
            curRow['dayHigh'] = row.dayHigh
            curRow['buyIndicator'] = "Y" if (row.buyIndicator == "YES") else "N"
            curRow['newEntrant'] = "N"
            curRow['increased'] = row.goodChange
            curRow['dayLow'] = row.dayLow
            curRow['previousClose'] = row.previousClose
            if (getfloat(row.previousClose) != 0):
                curRow['gainVal'] = round(getfloat(row.CurPrice) - getfloat(row.previousClose), 2)
            else:
                curRow['gainVal'] = "-"

            curRow['threshold'] = "Y" if (getfloat(row.threshold) <= getfloat(row.Gain)) else "N"

            text = text + row.SMSSymbol
            if (row.buyIndicator == 'YES'):
                text = text + "*"
            text = text + "[" + row.CurPrice + " " + row.Gain + "] "

            curBucket = int(math.ceil((curPos + 1) / 5.0))
            smsText = ""
            if (row.Symbol in self.bucketSMSList):
                sentBuckets = self.bucketSMSList[row.Symbol]
                minBucket = min(sentBuckets or [5])
                # If symbol's current bucket is not yet used for SMS and this is a better bucket than earlier
                if ((not (curBucket) in sentBuckets) | curBucket < minBucket):
                    smsText = smsText + row.SMSSymbol
                    if (row.buyIndicator == 'YES'):
                        smsText = smsText + "*"

                    smsText = smsText + "[" + row.CurPrice + " " + row.Gain + "]"

                    sentBuckets.append(curBucket)
                    self.bucketSMSList[row.Symbol] = sentBuckets
                    curRow['newEntrant'] = "Y"
                    # print bucketSMSList[row.Symbol]
                    smsPerBucket[curBucket].append(curRow)
                else:
                    smsPerBucket[curBucket].append(curRow)
            else:
                self.bucketSMSList[row.Symbol] = [curBucket]
                smsText = smsText + row.SMSSymbol
                if (row.buyIndicator == 'YES'):
                    smsText = smsText + "*"
                smsText = smsText + "[" + row.CurPrice + " " + row.Gain + "]"
                curRow['newEntrant'] = "Y"
                smsPerBucket[curBucket].append(curRow)
            curPos += 1

        return smsPerBucket, text

    def getLatestShareList(self):
        # print "Getting Data"
        fetchCount = next(self.fetchCounter)
        self.df = getSharesInfo(self.sourceLink, fetchCount)

        self.filter_shares()

        self.que.append(self.df)
        result, latestList = self.combineResults()

        goodScrips = self.analyzeResults( result, latestList)

        goodScrips = self.getVolumesForGoodScrips(goodScrips)

        # print goodScrips
        bucketSMSList, text = self.getSMSText( goodScrips )

        return bucketSMSList, text
