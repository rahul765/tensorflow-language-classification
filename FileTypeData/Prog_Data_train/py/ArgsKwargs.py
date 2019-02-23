"""
Understanding Args and Kwargs

What will be the output from the following function calls
"""


def f(x, y, z):
    # uncomment for debug
    # print "x=%s y=%s, z=%s" % (x, y, z)
    return (x + y) / float(z)


print(f(10, 5, 3))  # 5.0

x = 20
y = 10
z = 3

# using kwargs
print(f(x=x, y=y, z=z))  # 10.0

# using args and kwargs
print(f(y, x, z=2))  # 15.0

# using args and kwargs out of order
print(f(z=3, y=x, x=y))  # 10.0

# all x
print(f(z=x, y=x, x=x))  # 2.0

"""
With defaults
"""


def ff(x=20, y=10, z=3):
    # uncomment for debug
    # print "x=%s y=%s, z=%s" % (x, y, z)
    return (x + y) / float(z)


print(ff(10, 5, 3))  # 5.0

# using args
print(ff(10))  # 6.6666...

# using kwargs
print(ff(z=10))  # 3.0

x = 20
print(ff(z=x, y=x))  # 2.0
