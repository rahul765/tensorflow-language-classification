#include <iostream>
using namespace std;

void bubbleSort(int* a, int length) {
  bool swapped = true;
  for (int i=0; (i<length) && (swapped); i++) {
  	swapped = false;
  	for (int j=0; j<length-i-1; j++) {
  		if (a[j] > a[j+1]) {
  			int temp = a[j];
  			a[j] = a[j+1];
  			a[j+1] = temp;
  			swapped = true;
      }
  	}
  }
}

int main() {

	int a [] = {10,9, 8, 7, 6, 5, 4, 3, 2, 1};
	int length = sizeof(a)/sizeof(int);
	bubbleSort(a, length);
	for (int i=0; i<length; i++) {
		cout << a[i]<<" ";
	}
}