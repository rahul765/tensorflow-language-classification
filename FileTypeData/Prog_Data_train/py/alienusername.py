import re
pattern=r'^[_|.][0-9]+[a-zA-Z]*_{0,1}$'
for _ in range(int(raw_input())):
    msg=raw_input()
    if re.search(pattern,msg):
               print "VALID"
    else:
               print "INVALID"


