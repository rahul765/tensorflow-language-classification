#include <iostream>
#include <algorithm>
using namespace std;
int main(){
	short P, ds, m, t, lf; int n[9999];
	cin >> P;
	while (P--){
		lf = 1;
		cin >> ds >> m;
		printf("%d %d\n", ds, (m + 1) / 2);
		for (t = 0; t < m; t++)
		{
			cin >> n[t];
			if (t % 2 == 0){
				sort(n, n + t + 1); printf("%d ", n[t / 2]);
				if (lf++ == 10){ printf("\n"); lf -= 10; }
			}

		}
		printf("\n");
	}

}
