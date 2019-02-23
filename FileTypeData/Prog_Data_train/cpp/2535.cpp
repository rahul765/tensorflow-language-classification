#include<iostream>
#include<cstdio>
#define IB 0x7fffFFFF
typedef long long LL;
using namespace std;
int N,P,Min,Max,t[1000];
struct prob{int easy;bool isdif;}p[1000];
int main(){scanf("%d%d", &N, &P); bool valid = 0;
for (int _ = 0; _ < 1000;_++)p[_].easy = 0, _[p].isdif = 0;
	for (int __ = 0; __ < N; __++){
		Min = IB, Max = 0;
		for (int _ = 0; _ < P;_++)
		{scanf("%d", _+t);if (t[_] > Max)Max = _[t]; if (t[_]<Min)Min = _[t];}
		for (int _ = 0; _ < P;_++)
		{if (t[_] == Max)p[_].isdif = 1;if (t[_] == Min)p[_].easy++;}
	}
	for (int _ = 0; _ < P; _++)if (_[p].easy>N / 2 && !p[_].isdif)valid = 1, printf("%d ", _+1);
	if (!valid)puts("0"); return 0;
}
