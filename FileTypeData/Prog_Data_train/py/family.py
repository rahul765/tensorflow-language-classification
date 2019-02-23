
from traits.api import (Delegate, HasTraits, Instance, Int, Str)


class Parent(HasTraits):
    last_name = Str('') 


class Child(HasTraits):
    age = Int

    father = Instance(Parent)

    last_name = Delegate('father') 

    def _age_changed(self, old, new): 
        print('Age changed from %s to %s ' % (old, new))

if __name__ == '__main__':
    father = Parent(last_name='Joe')
    child = Child(age=2, father=father)
    child.configure_traits()

