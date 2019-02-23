#!/usr/bin/python3


def find_max_path(reversed_triangle):
    row_best = reversed_triangle[0]
    for row in reversed_triangle[1:]:
        new_best = []
        for i in range(len(row)):
            new_best.append(row[i] + max(row_best[i], row_best[i+1]))
        row_best = new_best

    return row_best


with open('p067_triangle.txt', 'r') as file:
    lines = file.readlines()
    triangle = [[int(s_num) for s_num in line.split()] for line in lines]
    reversed_triangle = list(reversed(triangle))
    print(find_max_path(reversed_triangle))
