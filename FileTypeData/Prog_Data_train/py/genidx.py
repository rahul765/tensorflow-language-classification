#!/usr/bin/env  python
#-*- coding:utf-8 -*-

import conf

dbs = ['wdb32', 'wdb16', 'wdb40', 'wdb64', 'wdb96', 'wdb128']
wdtypes = {
           "wdb32":["mmd5","md32","mmmd5","ntlm","msha1"],
           "wdb16":['md16', 'sqlmy', 'mmd5_16'],
           "wdb40":['sha1', 'sqlmy5', 'sha1md5', 'ssha1'],
           "wdb64":['sha256'],
           "wdb96":['sha384'],
           "wdb128":['sha512']
           }

wdtypes = {
           "wdb32":["passwd"],
           "wdb16":['passwd'],
           "wdb40":['passwd'],
           "wdb64":['passwd'],
           "wdb96":['passwd'],
           "wdb128":['passwd']
           }

def main():
    for db in dbs:
        print "\n    ################################\n"
        ss = '    db=conn.%s\n' % (db)
        print ss
        for col in conf.NSTR:
            for wdtype in wdtypes[db]:
                ss = '    db.%s.create_index("%s", unique=True)' %(col, wdtype)
                print ss

if "__main__" == __name__:
    main()
