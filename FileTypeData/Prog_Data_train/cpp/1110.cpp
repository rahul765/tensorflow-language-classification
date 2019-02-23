#include<iostream>
#include<string>
using namespace std;
void p(string&ss){ cout<<ss<<endl; }
inline void rp(string&big,char f[]){
	string find(f);
	while (~big.find(find))big.replace(big.find(find),find.length(),"0");
}
int main(){
	string s;int len;
	while (cin>>s){
		len=s.length();
		for (int _=0;_<len;_++)if (s[_]=='1')s[_]='0';
		while ((len=s.length())>1){
			rp(s,"00");rp(s,"(0)");rp(s,"0*");rp(s,"0|0");
			if (len==s.length()){puts("no");goto nx;}
		}if (s=="0")puts("yes");else puts("no");
	nx:;
	}
}