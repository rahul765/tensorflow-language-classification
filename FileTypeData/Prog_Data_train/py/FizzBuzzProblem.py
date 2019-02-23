__author__ = 'asethi'
#FizzBuzz Problem.
# If X=15 then print " 1,2,Fizz,4,buzz,Fizz,7,8,Fizz,Buzz,11,Fizz,13,14,FizzBuzz"

x=16

for num in range (1,x):
    if num%3==0 and num%5==0:
        print "FizzBuzz",
    elif num%3==0:
        print "Fizz",
    elif num%5==0:
        print "Buzz",
    else:
        print num,




