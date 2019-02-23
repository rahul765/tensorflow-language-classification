#include <iostream>
using namespace std;
int main(){
	bool ceil[101]; bool *p = ceil;
	int round; short n, t, open;
	cin >> round;
	while (round--){
		memset(ceil, 0, sizeof(ceil));
		cin >> n; open = 0;
		for (t = 1; t <= n; t++){
			p = ceil;
			while ((p += t) <= &ceil[100])*p^=1;
		}
		for (t = 1; t <= n; t++) if (ceil[t])open++;
		printf("%d\n", open);
	}
	return 0;
}
