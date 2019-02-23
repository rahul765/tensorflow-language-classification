#include <iostream>
#include<algorithm>
using namespace std;
short g[101]; short x;
int main(){
	int k,s=0; cin >> k;
	for (x = 0; x < k; x++)cin >> g[x];
	sort(g, g + k);
	x = k / 2 + 1;
	for (k = 0; k < x; k++)s += g[k]/2+1;
	cout << s << endl;
	return 0; }
