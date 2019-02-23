def factorial(n):
    '''
    Prints a list of factorials, their values and the result
    e.g

    1: 1
    2: 2 * 1 = 2
    3: 3 * 2 * 1 = 6
    '''
    product = 1
    for i in range(1, n+1):
        product = product * i
    return product

if __name__ == '__main__':
    selection = raw_input('Enter a number:')
    print 'The factorial of %s is %s' % (selection, factorial(int(selection)))
