#include<bits/stdc++.h>
using namespace std;
#define MAXS 32
struct fs{
	short factor,occurance;
	fs(){factor=0;occurance=0;}
	}k[MAXS];
int main(){
	short r=0,t,p,test,e,fe,ko;bool mark;
	int N,M,K;cin>>N>>M>>K;
	p=0;if ((ko=K)==1)goto ski;
	for (test=2;K>1;test++){
		mark=0;
		while (!(K%test))K/=test,k[p].factor=test,k[p].occurance++,mark=1;
		if (mark)p++;
	}e=p;
	ski:while (N--){
		cin>>t;
		if (t==1&&ko>1)continue;
		if (ko==1){r++;continue;}
		p=0;fs n[MAXS];
		int _=0;
		for (test=2;t!=1;test++){
			mark=0;
			while (!(t%test))t/=test,n[p].factor=test,n[p].occurance++,mark=1;
			if (mark)p++/*,printf("%d--%d\n",n[p-1].factor,n[p-1].occurance)*/;
		}fe=p;p=0;
		for (int _=0;_<e;_++){
			while (k[_].factor!=n[p].factor){p++;if (p==fe)goto nx;}
			if (k[_].occurance<=n[p].occurance*M){continue;}
			else goto nx;
		}r++;
	nx:;
	}
	printf("%d",r);
}