from itertools import count

counter = count()

def getNextNum():
    i = 1
    while (True):
        yield i
        i += 1