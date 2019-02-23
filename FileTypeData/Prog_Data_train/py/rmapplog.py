'''
Created on 2015-8-31

@author: xie
'''
import commands,time,os
APP_LOG_PATH = "/home/hadoop/logs/"


def rmAppLogName(now_time):
    file_dirs = os.listdir(APP_LOG_PATH)
    for file_dir in file_dirs:
        if not file_dir or not file_dir.startswith("app-"):
            continue
        if file_dir > now_time:
            continue
        commands.getoutput("rm -rf "+APP_LOG_PATH+file_dir)


if __name__ == '__main__':
    now_time = time.strftime("%Y%m%d%H%M%S",time.localtime(time.time() - 1200))
    rmAppLogName("app-"+now_time)
    pass
