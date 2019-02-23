import sys

nc=int(raw_input())
for tc in range(nc):
	n=int(raw_input())
	p=[]
	for i in range(n):
		inp=raw_input().split()
		name=inp[0].split(':')[0]
		c=inp[1].split('-')
		c.reverse()
		c=[x.replace('lower','z') for x in c]
		c=[x.replace('upper','a') for x in c]
		a=['middle' for x in range(10)]
		a[0:len(c)]=c
		a.append(name)
		p.append(a)
	p=sorted(p)
	for i in range(n):
		print p[i][10]
	print '='*30
