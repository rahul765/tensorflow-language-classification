#include <iostream>
using namespace std;
int main(){
	long long a, b;
	int x, y; cin >> x;
	for (y = 1; y <= x; y++){
		cin >> a >> b;
		printf("Scenario #%d:\n", y);
		printf("%lld\n\n", (b - a + 1)*(b + a) / 2);
	}
	return 0;
}
