'''
Created on 2014-2-11

@author: xie
'''

import os,time,commands

if __name__ == '__main__':
    timeStr = time.strftime("%Y-%m-%d",time.localtime(int(time.time()) - 86400))
    print commands.getoutput("/home/hadoop/hadoop/spark/bin/spark-submit --master spark://master:7077 --executor-memory 6g  --class spark.tan14.cn.analysisLog /home/hadoop/bigdata.jar")
