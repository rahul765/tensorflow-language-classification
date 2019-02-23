#include <iostream>
using namespace std;
int main(){
	double e = 2.5, i, x = 2;
	printf("n e\n- -----------\n0 1\n1 2\n2 2.5\n");
	for (i = 3; i < 10; i++)
		printf("%d %.9lf\n", (int)i, e += (1 / (x *= i)));
	return 0;
}
