#argument 1 db name
#argument 2 collection 
#argument 3 output file name
import sys
import os
from bson import json_util
import pymongo
from pymongo import MongoClient
client = MongoClient()
db = client[sys.argv[1]]
read= db[sys.argv[2]]
res=[]

import json
with open(sys.argv[3], 'a') as outfile:
    for i in read.find():
	
	json.dump(i, outfile,default=json_util.default)
	outfile.write('\n')
		
	
	
	

