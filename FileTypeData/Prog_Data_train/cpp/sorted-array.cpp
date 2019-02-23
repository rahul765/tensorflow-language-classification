#include <iostream>
#include <algorithm>

using namespace std;

int main () {

	int array[] = {1,4,5,7,2,3,6,9,8};
	int size = sizeof(array) / sizeof(array[0]);


	// std::sort function from algorithm module in STL
	sort(array, array+size);

	for (int i=0 ; i<size ; i++) {
		cout << array[i] << " ";
	}

	cout << endl;

	return 0;
}