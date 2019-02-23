def primefactors(number):
    list=[]
    x=2
    while x<=number:
        if number%x==0:
            number/=x
            list.append(x)
        else:
            x+=1
    return list
answer=max(primefactors(600851475143))
print(answer)