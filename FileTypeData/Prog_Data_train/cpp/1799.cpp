#include <iostream>
#include<cmath>
using namespace std;
int main(){ 
	int N; double n, R; cin >> N;
	for (int c=1; c <= N; c++){
		printf("Scenario #%d:\n", c);
		cin >> R >> n;
		// r/(sin(pi/n))+r=R
		printf("%.3f\n\n", R*sin(3.1415926 / n) / (1 + sin(3.1415926 / n)));
	}
	return 0; }
