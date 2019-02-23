
import pandas
from itertools import combinations
import matplotlib.pyplot as plt


url = "D:\\deep_udacity\\deep-learning\\PortoSeguro\\test.csv"

train_df = pandas.read_csv(url )

print train_df.shape

#
# plt.interactive(False)
#
# fig, axes = plt.subplots(len(train_df.columns)//2, 2, figsize=(12, 48))
#
# i = 0
# for triaxis in axes:
#     for axis in triaxis:
#         train_df.hist(column = train_df.columns[i], bins = 100, ax=axis)
#         i = i+1
#
# plt.show()
