import re
data = "".join([raw_input() for _ in range(int(raw_input()))])  
pattern=r'<[\s]*([\w].*?)>'
out=re.findall(pattern,data)
#print ",".join(v for v in out)
print out
