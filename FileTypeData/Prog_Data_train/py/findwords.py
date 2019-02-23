import re
data=" ".join(raw_input() for _ in range(int(raw_input())))
for _ in range(int(raw_input())):
    word=raw_input().strip()
    pattern=r'\b'+re.escape(word)+r'\b'
    print len(re.findall(pattern,data))
