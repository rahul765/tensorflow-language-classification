#include <iostream>
#include <vector>

using namespace std;

int main () {

	// Using multi-dimensional array

	int array[3][3] = {1,2,3,4,5,6,7,8,9};
	int size = sizeof(array) / sizeof(array[0]);

	for (int i=0 ; i<size ; i++) {
		cout << endl;
		for (int j=0 ; j<size ; j++) {
			cout << array[i][j] << " ";
		}
	}

	cout << endl;

	// Using C++ STL vector
	vector<vector<int> > v { { 1, 2, 3 }, { 4, 5, 6 } };	

	return 0;
}