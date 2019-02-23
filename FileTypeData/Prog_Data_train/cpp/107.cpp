#include<bits/stdc++.h>
using namespace std;

int main(){
	int N;cin>>N;
	if (N<=8){ putchar('0');return 0; }
	if (N==9){ putchar('8');return 0; }
	cout<<"72";N-=9;while (--N)putchar('0');
	/*
	111111111
	119357639
	380642361
	388888889
	611111111
	619357639
	880642361
	888888889
	*/
}