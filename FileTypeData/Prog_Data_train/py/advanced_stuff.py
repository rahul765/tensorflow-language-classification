# Advanced stuff with Python. Fun with gerators, decorators etc

#Decorator which memoizes stuff. I orefer class decorators for understandability
class memoize:
	def __init__(self, function):
		self.function = function
		self.memoized = {}

	def __call__(self, *args):
		try:
			return self.memoized[args]
		except KeyError:
			self.memoized[args] = self.function(*args)
			return self.memoized[args]

@memoize
def fibonacci(n):
	if n in (0,1):
		return n
	return fibonacci(n-1) + fibonacci(n-2)

if __name__ == "__main__":
	print fibonacci(20)