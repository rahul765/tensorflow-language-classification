## For loop and conditions
for x in range(1, 100):
    if x % 5 == 0 and x % 3 == 0:
        print x,

print # only for newLine

for x in range(1, 50):
    if x % 5 == 0 or x % 3 == 0:
        print x,

print

# print array elements

list = [2, 4, 6, 8, 10, 12, 14, "amit", "A"]
for var in list:
    print var,

print

#############################
# while loop in Python

print "............while loop starts here..........."
val = 10
while (val > 0):
    print val,
    val = val - 2

print
print "............while loop ENDS here..........."
