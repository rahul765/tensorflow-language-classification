// Functors make code more extendable
// templates are problematic since values has to be resolved at compile time. Cant use variable.

#include <iostream>
#include <vector>
using namespace std;

class AddValue {
	int val;

public:
	AddValue(int j) : val(j) { }
	void operator() (int j) {
		cout<<"Called functor and returned "<<val+j<<endl;
	}
};

int main() {
	vector<int> vec;
	// clang does not support initializer lists. Hence pushing back elements.
	vec.push_back(1);
	vec.push_back(2);
	vec.push_back(4);
	vec.push_back(10);
	int x = 2;
	for_each(vec.begin(), vec.end(), AddValue(x));
	for_each(vec.begin(), vec.end(), [=](int j){ cout<<"lambda value addition is "<<j+x<<endl; }); // lambda capture by copy/value
	// clang doesnt seemt to support c++11 by default. compile with -std=c++11
}