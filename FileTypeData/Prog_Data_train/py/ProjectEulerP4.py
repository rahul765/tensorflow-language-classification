def palindrome(num):
    if str(num) == str(num)[::-1]:
        return True
    else:
        return False

def product():
    anslist = []
    for a in range(999,99,-1):
        for b in range(999,99,-1):
            product = a*b
            if palindrome(product) == True:
                anslist.append(product)
    return max(anslist)
