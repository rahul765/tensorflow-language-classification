#include <iostream>
#include<algorithm>
using namespace std;
int main(){ 
	int m,n,k,d[1024],t; cin >> m;
	while (m--){
		scanf("%d%d", &n, &k);
		for (t = 0; t < n; t++)scanf("%d", &d[t]);
		while (k--)next_permutation(d, d + n);
		for (t = 0; t < n - 1; t++)printf("%d ", d[t]); 
		printf("%d\n", d[n - 1]);	
	}
	return 0;}
