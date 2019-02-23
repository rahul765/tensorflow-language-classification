import re

data = "".join([raw_input() for _ in range(int(raw_input()))])
links = re.findall(r"<\s*a [^>]*href=\"([^\"]*)\"[^>]*>(.*?)</a", data)
links = [href.strip() + "," + re.sub("<[^>]*>", "", title.strip()) for href, title in links]
print "\n".join(links)
