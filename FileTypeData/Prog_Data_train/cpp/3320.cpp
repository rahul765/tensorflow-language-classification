#include<iostream>
#include<cstdio>
#include<set>
#include<map>
#include<algorithm>
#define IB 0x7fffFFFF
using namespace std;
int r,total,p,__;
map<int,int>p2p;set<int>fm;//point to page,pages
int main(){
	scanf("%d",&total);
	for (int _=1;_<=total;_++)
	{
		scanf("%d",&p);
		if (__=p2p[p])fm.erase(fm.find(__));else r=IB;
		fm.insert(_);p2p[p]=_;r=min(1+_-*fm.begin(),r);
	}printf("%d\n",r);
}
