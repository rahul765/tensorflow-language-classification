#include<bits/stdc++.h>
using namespace std;
//int _3(int i){
//	if (i<10)return i%3;
//	return _3(i/10+i%10);
//}
/*100100100100....*/
int main(){
	int N;scanf("%d",&N);
	printf("%d",N/3*2+(N%3==2?1:0));
}