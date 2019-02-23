#include<iostream>
#define mod 10000
using namespace std;
struct smatrix{
	smatrix(){ a=b=c=1;d=0; }
	int a,b,
	    c,d;
}table[32];
smatrix operator*(smatrix const&a,smatrix const&b){
	smatrix c;
	c.a=a.a*b.a+a.b*b.c;c.b=a.a*b.b+a.b*b.d;
	c.c=a.c*b.a+a.d*b.c;c.d=a.c*b.b+a.d*b.d;
	c.a%=mod;c.b%=mod;c.c%=mod;c.d%=mod;
	return c;
}
int main(){
	int n;bool hasbeenset;smatrix re;
	for (int i=1;i<32;i++)table[i]=table[i-1]*table[i-1];
	while (scanf("%d",&n)){
		if (n==-1)return 0;if (!n){ puts("0");continue; }
		hasbeenset=0;
		for (int i=0;i<32;i++)
		{
			if (n>>i&1)
				if (!hasbeenset)re=table[i],hasbeenset=1;
				else re=re*table[i];
		}
		printf("%d\n",re.b);
	}
}
