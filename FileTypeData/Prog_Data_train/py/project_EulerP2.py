sum=0
a,b=0,1
while a<4000000:
    if a % 2 == 0:
        sum=sum+a
    c=a+b
    a,b=b,c
print (sum)