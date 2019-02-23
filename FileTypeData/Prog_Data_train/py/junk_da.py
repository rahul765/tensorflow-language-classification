import sys
import math

def f(t,j1,j2):
	dx = j1[0] + t * j1[4] - j2[0] - t * j2[4]
	dy = j1[1] + t * j1[5] - j2[1] - t * j2[5]
	dz = j1[2] + t * j1[6] - j2[2] - t * j2[6]
	return math.sqrt(dx*dx+dy*dy+dz*dz)

nc=int(raw_input())
for tc in range(nc):
	j1=map(float,raw_input().split())
	j2=map(float,raw_input().split())
	d=j1[3]+j2[3]
	
	lo=0
	hi=1e30
	for i in range(500):
		a=(2.0*lo+hi)/3.0
		b=(lo+2.0*hi)/3.0
		if(f(a,j1,j2)<f(b,j1,j2)):
			hi=b
		else:
			lo=a
	
	if(hi>=1e30 or f(lo,j1,j2)>d+1e-15):
		print 'No collision'
	else:
		lo=0
		for i in range(300):
			m=0.5*(lo+hi)
			if(f(m,j1,j2)<d):
				hi=m
			else:
				lo=m
		print str.format("{0:.3f}",hi)			
