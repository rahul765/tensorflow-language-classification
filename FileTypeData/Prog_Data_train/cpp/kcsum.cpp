#include <iostream>
using namespace std;

int main() {

int k;
int size;
int sum;

cin >> k;
cin >> size;

int data[size];
for(int i=0; i<size; i++) cin >> data[i];

for(int i = 0; i<size; i++) {
	sum = 0;
	for(int j = 0; j<size-i, sum<=k; j++) {
		sum = data[i+j] + sum;
		if(sum == k) {
			for(int m=0; m<=j; m++) cout<< data[i+m] << " ";
			cout << endl;
			break;
		}

}
}
}

