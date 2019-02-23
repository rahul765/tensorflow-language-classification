class Person:

	def __init__(self, id, firstName, lastName):
		self.id = id
		self.firstName = firstName
		self.lastName = lastName
		
	def __str__(self):
		return "{} {}".format(self.firstName, self.lastName)
		
	def __repr__(self):
		return self.__str__()


peopleData = {
	2 : {"firstName" : "Jane", "lastName" : "Campion"},
	4 : {"firstName" : "Lee", "lastName" : "Tamahori"},
	6 : {"firstName" : "Taika", "lastName" : "Waititi"}
}

people = {id: Person(id, names["firstName"], names["lastName"]) for id, names in peopleData.items()}

print(people)
