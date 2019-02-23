def cluster(data):
    for i in range(0, len(data), 2):
        yield ord(data[i]) + (ord(data[i+1]) << 8)

