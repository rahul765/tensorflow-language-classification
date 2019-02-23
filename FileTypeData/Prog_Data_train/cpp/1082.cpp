#include<iostream>
#include<string>
#include<algorithm>
#define f "marshtomp"
#define t "fjxmlhx"
using namespace std;
int main(){
	string a,b;
	while (getline(cin,a)){
		b=a;for (int i=0;i<b.length();i++)if (b[i]>='A'&&b[i]<='Z')b[i]+=32;
		while (~b.find(f))
		{
			a.replace(b.find(f),9,t);
			b.replace(b.find(f),9,t);
		}
		cout<<a<<endl;
	}
}