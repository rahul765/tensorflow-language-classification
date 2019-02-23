#include<iostream>
using namespace std;
double n=1,tr=1;int i=4,e2=0,q=0;
int result[25];
//1960 1970....
int main(){
	while (i<=8388608){
	//4 1960
	//4194304 2160 8388608 2170
		tr*=n++;while (tr>1)tr/=2,e2++;
		if (i<e2)i<<=1,result[q++]=n;
	}
	while (scanf("%d",&q)&&q)printf("%d\n",result[q/10-196]-2);
}