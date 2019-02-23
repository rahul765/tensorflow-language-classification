#include<bits/stdc++.h>
using namespace std;
int main(){
	int k,n,p,l;short c,t[8000];
	scanf("%d%d",&k,&n);
	for (int _=0;_<n;_++)scanf("%d",t+_);
	sort(t,t+n);
	p=3;c=t[0]/1000;l=k;
	for (int _=0;_<n;_++)
		if (c==t[_]/1000)if (l)l--;else p++,l=k-1;else p++,c=t[_]/1000,l=k-1;
	printf("%d\n",p);
}