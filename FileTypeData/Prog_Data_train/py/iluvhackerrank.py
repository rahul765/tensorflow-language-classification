# Enter your code here. Read input from STDIN. Print output to STDOUT
import re
data="".join(raw_input() for _ in range(int(raw_input())))
pattern=r'[h|H][a|A][c|C][k|K][e|E][r|R]{2}[a|A][n|N][k|K]'
out=re.findall(pattern,data,re.I)
print len(out)
