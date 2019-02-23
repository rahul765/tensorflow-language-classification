#include<bits/stdc++.h>
#include<biginteger>
using namespace std;
string wtf(int in){
	string r;
	while (in)r=(char)(in%10+48)+r,in/=10;
	return r;
}
bool operator>(BigNumber&a,BigNumber&b){
	string x=a.toString(),y=b.toString();
	if (x.length()!=y.length())return x.length()>y.length();
	for (int _=0;_<y.length();_++)
		if (x[_]!=y[_])return x[_]>y[_];
	return 1;
}
int main(){
	int a,b;BigNumber sh,it;
	cin>>a>>b;
	BigNumber aa(wtf(a)),bb(wtf(b));
	sh=pow(aa,b);it=pow(bb,a);
	if (sh>it)
	cout<<(sh-it).toString()<<endl;
	else
	cout<<'-'<<(it-sh).toString()<<endl;
}