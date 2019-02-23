
#
# Calculate sqrt using Newton Raphson Method 
#
# req: x >= 0
#

EPSILON = 0.0000000001

def nrsqrt(x):
    lower = float(0)
    higher = float(x)

    while higher - lower > EPSILON:
        mid = lower + (higher - lower) / 2.0
        mid_p2 = mid * mid

        if x == mid_p2:
            return mid
        elif x > mid_p2:
            lower = mid
        else:
            higher = mid
    
    return lower + (higher - lower) / 2.0

print(nrsqrt(5435))


