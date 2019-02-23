from __future__ import division
import pandas as pd


def triangle_area(a_x, a_y, b_x, b_y, c_x, c_y):
    return abs(a_x * (b_y - c_y) + b_x * (c_y - a_y) + c_x * (a_y - b_y)) / 2


def contains_origin(a_x, a_y, b_x, b_y, c_x, c_y):
    return triangle_area(a_x, a_y, b_x, b_y, c_x, c_y) == \
           (triangle_area(0, 0, b_x, b_y, c_x, c_y) +
            triangle_area(a_x, a_y, 0, 0, c_x, c_y) +
            triangle_area(a_x, a_y, b_x, b_y, 0, 0))


if __name__ == '__main__':
    triangles = pd.read_csv('p102_triangles.txt').values
    result = sum([contains_origin(*triangle) for triangle in triangles])
    print(result)
