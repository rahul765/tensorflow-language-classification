# Rolls through all files in a specified path and filters out those files whose encoding is not UTF-8

import os

def keep_utf8(path):
    
    for root, dirs, files in os.walk(path):
        for file in files:
            abs_file = os.path.join(root, file)
            try:
                open(abs_file, encoding='utf-8', mode='r').read()
            except UnicodeDecodeError:
                print(file)
                os.remove(abs_file)
