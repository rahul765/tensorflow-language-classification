import sys

nc = int(raw_input())
for tc in range(1, nc+1):
	rpd = map(int, raw_input().split())
	nwp=[]
	for i in range(rpd[0]):
		nwp.append(raw_input().split())
	mw=[(float(x[1])*rpd[2]/rpd[1]) for x in nwp if float(x[2])==100.0]
	res=[(x[0],0.01*float(x[2])*mw[0]) for x in nwp]
	
	print str.format("Recipe # {0}",tc)
	for i in range(0,rpd[0]):
		print str.format("{0} {1:.1f}",res[i][0],res[i][1])
	print '-'*40
