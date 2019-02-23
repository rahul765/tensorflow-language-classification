#include<bits/stdc++.h>
#define rt(a,b) if(in>(a)*1e3*b)_+=(a)*1e3,in-=(a)*1e3*b;else{ _+=in/b;goto o;}
using namespace std;
double in,_=0;
int main(){
	cin>>in;
	rt(1.5,.03)
	rt(4.5-1.5,.1)
	rt(9-4.5,.2)
	rt(35-9,.25)
	rt(55-35,.3)
	rt(80-55,.35)
	_+=in/.45;
	o:cout<<(int)floor(_+3500)<<endl;
}