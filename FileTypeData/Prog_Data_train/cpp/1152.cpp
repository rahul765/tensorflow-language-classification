#include<iostream>
#include<set>
#include<string>
#include<algorithm>
#include<bits/stdc++.h>
using namespace std;
set<string>w;
inline bool isfib(int x){ return x==1||x==2||x==3||x==5||x==8||x==13||x==21; }
int main(){
	char i[101];int unique;bool a[26];
	scanf("%s",i);
	for (int _=0;_<strlen(i);_++){
		fill(a,a+26,0);string out;unique=0;
		for (int __=_;__<strlen(i);__++)
		{
			out+=__[i];
			if (!a[i[__]-'a'])a[__[i]-'a']=1,unique++;
			if (isfib(unique))w.insert(out);
			if (unique>21)break;
		}
	}
	while (!w.empty())cout<<*w.begin()<<endl,w.erase(w.begin());
}