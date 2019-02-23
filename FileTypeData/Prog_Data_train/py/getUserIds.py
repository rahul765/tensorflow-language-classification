import os
import json
from itertools import izip

def getUserIds():
  write_file = open("./original_json/userids.txt","w")
  for company_folder in open("LOC.txt").readlines():
    for file in os.listdir("./original_json/"+company_folder.strip()+"/"):
      if file.endswith(".json") and "_" not in file:
        json_data = open("./original_json/"+company_folder.strip()+"/"+file).read()
        data = json.loads(json_data)
        if len(data):
          write_file.write(str(data[0]['user']['id']) + "\n")
  write_file.close()
  json_data.close()

def join_files():

  with open("./classifiedTweets.txt") as tweets, open("./classificationResults.txt") as values:
    for tweet,value in izip(tweets,values):
      print tweet.strip(),value.strip()

if __name__ == "__main__":
  #getUserIds()
  join_files()