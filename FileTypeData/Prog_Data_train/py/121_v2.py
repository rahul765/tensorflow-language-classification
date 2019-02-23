'''
http://www.reddit.com/r/dailyprogrammer/comments/19mn2d/030413_challenge_121_easy_bytelandian_exchange_1/

Sample Input
------------
7

Sample Output
-------------
15

Challenge Input
---------------
1000

Challenge Input Solution
------------------------
?
'''

def func(n):
    '''
    using recursion
    '''
    print 'what is n', n
    if n == 0:
        return 2
    else:
        return func(n/2) + func(n/3) + func(n/4)

if __name__ == '__main__':
    selection = raw_input('Enter a number:')
    print 'final solution:', func(int(selection))
