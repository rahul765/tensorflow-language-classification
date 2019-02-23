#include<bits/stdc++.h>
using namespace std;
inline int gcd(int a,int b){
	return b?gcd(b,a%b):a;
}
int main(){
	int N,r=0;scanf("%d",&N);
	for (int i=1;i<=N;i++)if (gcd(N,i)==1)r++;
	printf("%d",r);
}