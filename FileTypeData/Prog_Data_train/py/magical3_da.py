import sys

def primes(n):
	s = [True] * (n + 1)
	s[0] = s[1] = False
	for x in range(2, int(n**0.5)):
		if(s[x]):
			for y in range(x*x,n+1,x):
				s[y]=False
	s[2] = s[3] = False
	return [x for x in range(n+1) if s[x]]

def solve(n):
	if(n == 3):
		return 4
	if(n < 7):
		return 'No such base'
	n = n - 3
	for x in range(4, 10):
		if(n % x == 0):
			return x
	while(n % 3 == 0):
		n = n / 3
	while(n % 2 == 0):
		n = n / 2
	for q in p:
		if(n % q == 0):
			return q
	return n
	
p = primes(43640)

while(1==1):
	n = int(raw_input())
	if(n==0):
		exit(0)
	print solve(n)

