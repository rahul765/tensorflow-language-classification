from SharesInfo import *

gainers = SharesInfo("Gainers", 10, "http://money.rediff.com/gainers/bse/daily/groupa?src=gain_lose")

gainersDF = gainers.getLatestShareList()

print gainersDF

