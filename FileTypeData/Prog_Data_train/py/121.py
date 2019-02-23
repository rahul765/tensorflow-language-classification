
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

def rounded_down_value(n):
    '''
    takes an int and returns 3 rounded down values
    '''
    return [n/2, n/3, n/4]

if __name__ == '__main__':
    selection = raw_input('Enter a number:')
    zero_list = rounded_down_value(int(selection))

    while sum(zero_list) != 0:
        for num in zero_list:
            if num != 0:
                new_values = rounded_down_value(num)
                zero_list.remove(num)
                zero_list.extend(new_values)

    print len(zero_list)
