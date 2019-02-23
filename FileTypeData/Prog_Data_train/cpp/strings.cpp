// lets playaround with string in c++
#include <iostream>
using namespace std;

int main () {
	string s1 = "Harsh";
	string s2("Pathak");
	string name(s1, 0, 5);
	string fullname = s1 + s2;

  unsigned int sum = 0;
	for (int i=0; i<s1.size(); i++) {
		sum += s1[i];
	}

	cout << "The total value is "<<sum<<endl;
	cout << name <<endl;
	cout << fullname.size() <<" "<<fullname.length()<<endl;
	cout << fullname.capacity()<<endl;
	fullname.reserve(30);
	cout << fullname.capacity()<<endl;
	fullname.shrink_to_fit();
	cout << fullname.capacity()<<endl;
	fullname.resize(12,' ');
	cout<<fullname<<endl;

	cout << fullname.at(5) <<endl;
	fullname.insert(5, ".J.");
	cout << fullname <<endl;
	fullname.c_str();
	fullname.data();
}
