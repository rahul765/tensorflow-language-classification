#include <iostream>
#include<algorithm>
using namespace std;
char c[13];
int main(){
	int cs, t, len; cin >> cs;
	while (cs--){
		scanf("%s",c); len = strlen(c);
		for (t = 0; t < len; t++)
			if (c[t] <= 'Z')c[t] = (c[t] - 0x40) * 2;
			else c[t] = (c[t] - 0x60) * 2 + 1;
			sort(c, c + len);
			do{
				for (t = 0; t < len; t++)
					putchar( c[t] & 1 ? c[t] / 2 + 0x60 : c[t] / 2 + 0x40);
				putchar('\n');
			} while (next_permutation(c, c + len));
	}
	return 0;
}
