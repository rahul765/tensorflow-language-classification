import numpy as np


def min_sums_matrix(costs):
    min_sums = np.zeros(costs.shape)
    min_sums[0, :] = np.cumsum(costs[0, :])
    min_sums[:, 0] = np.cumsum(costs[:, 0])

    for i in range(1, min_sums.shape[0]):
        for j in range(1, min_sums.shape[1]):
            min_sums[i, j] = min(min_sums[i, j - 1], min_sums[i - 1, j]) + costs[i, j]

    return min_sums


p81_matrix = np.loadtxt('p081_matrix.txt', delimiter=',')
print(min_sums_matrix(p81_matrix))
