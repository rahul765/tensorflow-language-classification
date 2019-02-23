#include <iostream>
#include <cstring>
using namespace std;
int main(){
	int k, remain, st, t, m, n;
	bool guy[101], *s = &guy[1], *good, *e, *p;
	while (cin >> n){
		if (!n)return 0;
		e = &guy[n]; m = 0;
		while (++m){
			memset(guy, 1, sizeof(guy));
			p = s;
			*p = false; remain = n - 1;
			while (remain > 1){
				t = m%remain; if (!t)t = remain;
				//move p to next live guy
				while (t--){
					if (p < e)p++; else p = s;
					while (*p == false)if (p < e)p++; else p = s;
				}
				if (p == s + 12) goto nextm;
				*p = false; remain--;
			}
			//ok,remain=k && no good guy was killed
			goto nextk;
		nextm:;
		}
	nextk:cout << m << endl;
	}
}
