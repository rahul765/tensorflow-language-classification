#include<algorithm>
#include<iostream>
using namespace std;
int main(){
	pair<int, int> a[5000]; bool cut[5000];
	int _, __, i, t, cd, last; cin >> _; while (_--){
		cd = t = 0; memset(cut, 0, sizeof(cut));// not cut
		cin >> __; for (int x = 0; x<__; x++)scanf("%d%d", &a[x].first, &a[x].second);
		sort(a, a + __); for (int x = 0; cd<__; x++){
			if (!cut[x]){
				t++; cut[x] = 1; cd++; last = x[a].second;
				for (i = x + 1; i < __; i++)if (!cut[i] && a[i].second >= last)cut[i] = 1, last = a[i].second, cd++;
			}
		}
		printf("%d\n", t);
	}return 0;
}
