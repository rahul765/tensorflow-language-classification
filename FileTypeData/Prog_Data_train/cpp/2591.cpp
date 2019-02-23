#include <iostream>
#include<algorithm>
typedef long long LL;
using namespace std;
int s[10000001];
int main(){
	int N,a,b,x=1,y= 1;
	s[1] =1 ;
	for (int _ = 2; _ <= 10000000;_++)
	{
		s[_] = min(a = 2 * s[x] + 1, b = 3 * s[y] + 1);
		//may duplicate
		if(s[_] ==a)x++;if(s[_]==b) y++;
		//cout << s[_] << endl; getchar();
	}while (~scanf("%d", &N))printf("%d\n", s[N]);
	return 0;
}
