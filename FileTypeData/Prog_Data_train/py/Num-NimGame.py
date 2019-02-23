#!/bin/env python
'''
292 esay
https://leetcode.com/problems/nim-game/
'''

class Solution(object):
    def canWinNim(self, n):
        """
        :type n: int
        :rtype: bool
        1~3 Win
        1,2,3
        4 Lost
        5~7 Win
        8 Lost
        9~11 Win
        
        """

        if n <= 3:
            return True
        x = n/4
        y = (n-1)%4
        # y = x % 2
        # print 'x=%d ,y=%d' % ( x, y)
        if y == 3:
            res = False
        else:
            res = True
        return res

if __name__ == '__main__':
    s = Solution()
    for i in range(10):
        res = s.canWinNim(i)
        print '%d res: %s' % (i, res)
