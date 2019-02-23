'''
http://www.reddit.com/r/dailyprogrammer/comments/17f3y2/012813_challenge_119_easy_change_calculator/

Sample Input
------------
1.23

Sample Output
-------------
Quarters: 4
Dimes: 2
Nickels: 0
Pennies: 3

Challenge Input
---------------
10.24
0.99
5
00.06

Challenge Input Solution
------------------------
?
'''

import decimal

VALUES_LIST = [
    ('quarters', 25),
    ('dimes', 10),
    ('nickels', 5),
    ('pennies', 1),
]

if __name__ == '__main__':
    n = float(raw_input('Enter a number:'))
    n = n*100

    for i in VALUES_LIST:
        count, n  = divmod(n, i[1])
        if count != 0:
            print '%s %s' % (i[0], count)
