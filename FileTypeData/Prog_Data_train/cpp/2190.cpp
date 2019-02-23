#include <iostream>
using namespace std;
int main(){ 
	char i[11]; cin >> i;
	int p = 0,w,s;
	while (i[p] != '?')p++;
	for (s = 0; s < 10; s++)if (i[s] == 'X')i[s] = 10; else i[s] -= 0x30;
	for (int f = 0; f<10 || f <= 10 && p == 9; f++)
	{
		s = 0;
		for (w = 0; w < 10; w++)
			if (w == p)s += f*(10 - w); else s += i[w] * (10 - w);
		if (!(s % 11)){ if (f < 10)printf("%d\n", f);else printf("X\n"); return 0; }
	}printf("-1\n");
	return 0; }
