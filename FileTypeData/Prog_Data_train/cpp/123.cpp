#include<iostream>
using namespace std;
int main(){
	int k;cin>>k;
	if (k==1){putchar('1');return 0;}
	if (k==2){putchar('2');return 0;}
	unsigned int f[41]={0,1,1},a=2;
	for (int _=3;_<=k;_++)
		f[_]=f[_-1]+f[_-2],a+=f[_];
	printf("%d\n",a);
}