from InterestedStocks import *

intDF = loadInterestingStocks()

intDF = intDF[["Symbol"]]

resultsDF = loadResultsCalendarStocks()
result = pd.merge(intDF, resultsDF, on='Symbol')
result['BoardMeetingDate'] = pd.to_datetime(result.BoardMeetingDate)

for (idx, row) in result.iterrows():
    curSymbol = row.Symbol
    if(("Dividend") in row.Purpose ):
        result.ix[idx, 'Symbol'] = curSymbol + "#"
    elif(("Bonus") in row.Purpose):
        result.ix[idx, 'Symbol'] = curSymbol + "###"

result = result.drop(["Purpose"], axis=1)
result.sort_values(['BoardMeetingDate', 'Symbol'],   inplace=True)

print result

