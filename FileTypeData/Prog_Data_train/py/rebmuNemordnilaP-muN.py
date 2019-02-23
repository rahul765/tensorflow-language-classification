#!/bin/env python

'''
9. Palindrome numbers
https://leetcode.com/problems/palindrome-number/
'''

class Solution(object):
    def isPalindrome(self, x):
        """
        :type x: int
        :rtype: bool
        """
        res = True
        istr=str(x)
        j=len(istr)-1
        for i in range(len(istr)/2):
            if istr[i] != istr[j-i]:
                res = False
                break
        return res

if __name__ == '__main__':
    s=Solution()
    res=s.isPalindrome(123431)
    print res
