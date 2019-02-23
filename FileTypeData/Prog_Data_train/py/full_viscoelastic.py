from math import pi,factorial   
import numpy as np
import scipy.integrate as integrate

from utilities import A_integral

def image_coeff(t, n):
    result = 0
    for m in range(1, n+1):
        result += np.power(t, n - m) / factorial(n - m)
    result *= -np.exp(-t)
    result += 1
    return result

def solution(slip, x, t, alpha):
    images = 0.0
    addthis = 0.0
    n = 1
    while True:
        addthis = image_coeff(t, n) * (A_integral(slip, x, alpha, 2*n) + A_integral(slip, x, alpha, -2 * n))
        if (n != 1) and ((addthis == 0).all() or (abs(addthis/images) < 0.01).all()):
            break
        images += addthis
        n += 1

    outer_factor = x/pi
    main_fault_term = A_integral(slip, x, alpha, 0)
    result = outer_factor * (main_fault_term + images)
    return result

