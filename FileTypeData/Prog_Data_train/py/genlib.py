#!/usr/bin/env python
import conf

def main():
    for ii in conf.NSTR:
        if 3 < len(ii) and ii[1] in ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9']:
            jj = ii[1:]
        else:
            jj = ii
        
        #if ii[1] not in ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9']:
        #    print "ii is: {0} jj is {1}".format(ii, jj)
        ss = "        elif '%s' == type:\n            db.%s.insert(info)" % (jj, ii)
        print ss

if "__main__" == __name__:
    main()
