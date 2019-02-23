import sys

def find(x):
	while(x != s[x]):
		y = s[x]
		s[x] = s[s[x]]
		x = y
	return x
	
def union(x,y):
	sx=find(x)
	sy=find(y)
	if(sx==sy):
		c[sx]=c[sx]+1
	else:
		s[sy]=sx
		c[sx]=c[sx]+c[sy]

s=[]
c=[]
nc = int(raw_input())
while(nc):
	nc=nc-1
	n=int(raw_input())
	s=range(n+n+1)
	c=[0 for x in s]
	while(n):
		n=n-1
		card=map(int,raw_input().split())
		union(card[0],card[1])
	res=[x for x in c if x>1]
	if(len(res)):
		print 'impossible'
	else:
		print 'possible'
