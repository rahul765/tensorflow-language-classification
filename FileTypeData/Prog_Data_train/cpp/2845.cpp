#include <iostream>
using namespace std;
int main(){
	char ia[90], ib[90]; short *p, a[90], b[90], zero[90];
	int total, i; short t;
	short *e = &a[89];
	cin >> total;
	memset(zero, 0, sizeof(a));
	for (i = 1; i <= total; i++){
		scanf("%s %s", ia, ib);
		memset(a, 0, sizeof(a)); memset(b, 0, sizeof(a));
		p = &a[89];
		for (t = strlen(ia) - 1; t >= 0; t--) *(p--) = ((ia[t] == '1') ? 1 : 0);
		p = &b[89];
		for (t = strlen(ib) - 1; t >= 0; t--) *(p--) = ((ib[t] == '1') ? 1 : 0);
		if (!memcmp(a, zero, sizeof(a)) && !memcmp(b, zero, sizeof(a)))
		{
			printf("%d 0\n", i); continue;
			//被全0坑了一次，贡献了一个WA
		}
		for (t = 89; t >= 0; t--) {
			a[t] += b[t];
			while (a[t] > 1){ a[t] -= 2; a[t - 1]++; }
		}p = a; while (!*p)p++;
		printf("%d ", i);
		for (; p <= e; p++)printf("%d", *p);
		printf("\n");
	}
	return 0;
}
