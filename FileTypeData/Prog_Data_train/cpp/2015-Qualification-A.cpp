#include <iostream>
#include<cstring>
using namespace std;
inline int go(int x, int y, int m){ return y / m - x / m + (x%m ? 0 : 1)-(y%m?0:1); }
int main(){
	char t[15]; int __,a,b,p,q; 
	cin >> __;
	for (int _=1; _ <= __; _++){
		scanf("%s %d,%d", &t, &a, &b);p = b;
		if (strcmp("January", t) && strcmp("February", t))p++;
		scanf("%s %d,%d", &t, &a, &b); q = b;
		if (strcmp("January", t) && strcmp("February", t) || !strcmp("February", t)&&a==29)q++;
		if (p == q){ printf("Case #%d: 0\n", _); continue; }
		printf("Case #%d: %d\n", _, go(p, q, 4) - go(p, q, 100) + go(p, q, 400));
		}
	return 0;}
