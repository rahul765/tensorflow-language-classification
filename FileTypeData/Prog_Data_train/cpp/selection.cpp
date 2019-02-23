#include <iostream>
using namespace std;

void selectionSort(int* array, int length) {

	int min_pos;
	for (int i=0; i<length-1; i++) {
		min_pos = i;
		for (int j=i+1; j<length; j++) {
			if (array[j] < array[min_pos]) {
				min_pos = j;
			}
		}
		if (min_pos != i) {
			int temp = array[i];
			array[i] = array[min_pos];
			array[min_pos] = temp;
		}
	}
}

int main() {

	int a [] = {10,9, 8, 7, 6, 5, 4, 3, 2, 1};
	int length = sizeof(a)/sizeof(int);
	selectionSort(a, length);
	for (int i=0; i<length; i++) {
		cout << a[i]<<" ";
	}
}