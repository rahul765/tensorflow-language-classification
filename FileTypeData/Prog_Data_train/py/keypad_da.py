import sys

nc = int(raw_input())
while(nc > 0):
	nc = nc - 1
	m,n=raw_input().split()
	m,n=int(m),int(n)
	g=[]
	for i in range(m):
		g.append(raw_input())
	
	ok=True
	ans=[]
	for i in range(m):
		line=[]
		for j in range(n):
			h=len([x for x in range(n) if (x != j and g[i][x] == '1')])
			v=len([x for x in range(m) if (x != i and g[x][j] == '1')])
			if(g[i][j]=='0'):
				if(h*v):
					ok = False
				line.append('N')
			else:
				if(h*v):
					line.append('I')
				else:
					line.append('P')
		ans.append(line)
	
	if(ok):
		for i in range(m):
			print ''.join(ans[i])
	else:
		print 'impossible'
	print '-'*10
				