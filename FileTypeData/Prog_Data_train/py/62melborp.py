
def cycle_len(n):
    reminders = [1]
    reminder = 10 % n
    while reminder not in reminders:
        reminders.append(reminder)
        reminder = reminder * 10 % n

    return len(reminders[reminders.index(reminder):])

assert cycle_len(7) == 6

cycles = [(n, cycle_len(n)) for n in range(1, 1000)]
max_cycle = max(cycles, key=lambda c: c[1])
print(max_cycle)
