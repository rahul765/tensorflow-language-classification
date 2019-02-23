

#include <iostream>
using namespace std;
typedef long long LL;
#define MAX 11111111

LL arr[MAX], L[MAX/2+1], R[MAX/2+1];
LL total;

void Merge(LL *arr, LL p, LL q, LL r) {
	LL i, j, k, n1 = q-p+1, n2 = r-q;
	for( i = 0 ; i < n1 ; i++ ) L[i] = arr[p+i];
	for( j = 0 ; j < n2 ; j++ ) R[j] = arr[q+j+1];
	for( k = p , i = j = 0 ; k <= r ; k++ ) {
		if(j >= n2 || (i<n1 && L[i]<=R[j])) arr[k] = L[i++];
		else {
			total += n1-i;
			arr[k] = R[j++];
		}
	}
}

void Merge_Sort(LL *arr, LL p, LL r) {
	if(p<r) {
		LL q = (p+r)/2;
		Merge_Sort(arr,p,q);
		Merge_Sort(arr,q+1,r);
		Merge(arr,p,q,r);
	}
}

int main() {
	LL i, n, t;
	scanf("%lld", &t);
	while(t--) {
		scanf("%lld",&n);
		total = 0;
		for( i = 0 ; i < n ; i++ ) scanf("%lld",&arr[i]);
		Merge_Sort(arr, 0, n-1);
		// Print Total number of inversions present in the array
		printf("%lld\n", total);
	}
	return 0;
} 

/*
Input: 

1 // no. of test case

5
2
3
8
6
1

Output: 5
*/