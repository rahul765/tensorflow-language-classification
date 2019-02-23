#include <iostream>

using namespace std;

int main () {

	int array[] = {1,4,5,7,2,3,6,9};
	int size = sizeof(array) / sizeof(array[0]);

	for (int i=0 ; i<size ; i++) {
		cout << array[i] << " ";
	}

	cout << endl;

	return 0;
}