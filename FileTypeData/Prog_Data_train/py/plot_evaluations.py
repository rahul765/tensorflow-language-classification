from matplotlib import pyplot as plt

training_games = [0,10000,20000,30000,40000,50000,60000,70000,80000]
avg_scores = [0,5.071,6.045,5.353,8.643,8.748,10.201,13.780,16.987]
plt.plot(training_games,avg_scores)
plt.xlabel("Number of training games played")
plt.ylabel("Average AI score on 1000 games")
plt.title("AI Scores vs. Training Games Played")
plt.show()
