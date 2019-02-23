#include<iostream>
#include<algorithm>
using namespace std;
int N,B,H[20000],t=0,r=0;
int main(){
	cin>>N>>B;
	for (int i=0;i<N;i++)scanf("%d",H+i);
	sort(H,H+N);
	while (r+H[--N]<B)r+=H[N],t++;
	cout<<++t<<'\n';
}