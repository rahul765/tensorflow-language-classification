// In C++, STL vector implements a dynamic array

#include <iostream>
#include <vector>

using namespace std;

int main () {

	int array[] = {1,4,5,7,2,3,6,9};
	int size = sizeof(array) / sizeof(array[0]);

	vector<int> v(array, array+size);
	
	for (int i=0 ; i<v.size() ; i++) {
		cout << v[i] << " ";
	}

	cout << endl;

	return 0;
}