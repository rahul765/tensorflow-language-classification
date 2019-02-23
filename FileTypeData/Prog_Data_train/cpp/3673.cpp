#include<iostream>
using namespace std;
int a[15],b[15],r=0,A,B,_,__;
int main(){
	cin>>A>>B;
	for(_=0;A;++_)a[_]=A%10,A/=10;
	for(__=0;B;++__)b[__]=B%10,B/=10;
	while (~--_){
		for (int t=__;~t;--t)r+=_[a]*b[t];
	}
	printf("%d",r);
}