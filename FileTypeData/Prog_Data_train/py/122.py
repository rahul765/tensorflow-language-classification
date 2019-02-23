'''
http://www.reddit.com/r/dailyprogrammer/comments/1berjh/040113_challenge_122_easy_sum_them_digits/

Sample Input
------------
31337

Sample Output
-------------
8, because 3+1+3+3+7=17 and 1+7=8

Challenge Input
---------------
1073741824

Challenge Input Solution
------------------------
?
'''


def digital_root(n):
    '''
    takes a string, converts it into a list of int and returns the sum
    '''
    total = sum(map(lambda x:int(x), list(n)))
    return str(total)

if __name__ == '__main__':
    selection = raw_input('Enter a number:')

    total = digital_root(selection)
    while len(list(total)) > 1:
        total = digital_root(total)
    print total
