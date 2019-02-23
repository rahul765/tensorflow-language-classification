#include <iostream>
using namespace std;

int main() {

  int arr[] = {1,5,7,9,10,11,11,11};
  int length = sizeof(arr)/sizeof(int);
  int count = 0;
  sort(arr, arr+length);
  for (int i=0; i<length; i++) {
  	if (arr[i] != arr[i+1]) {
  		count++;
  	}
  }
  cout << count <<endl;
}