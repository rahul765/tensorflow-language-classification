#include<iostream>
#include<algorithm>
#define IB 0x7fffffff
using namespace std;
struct mymultiset_int{
	int e[100000],end;//end: the first invalid data
	mymultiset_int(){end=1;e[0]=IB;fill(e+1,e+100000,0); }
	void insert(int in){
		int i;e[i=end++]=in;
		while (i)if (e[i]>e[i/2])swap(e[i],e[i/2]),i/=2;else return;
	}
	int getmax(){return e[1];}
	void _delete(int i){
		swap(e[i],e[end-1]);end--;
		while (e[i]<e[2*i]&&2*i<end||2*i+1<end&&e[i]<e[2*i+1])
			if (2*i+1<end){
				if (e[2*i]>e[2*i+1])
					swap(e[i],e[2*i]),i*=2;else
					swap(e[i],e[2*i+1]),i=2*i+1;
			}
			else swap(e[i],e[2*i]),i*=2;
	}
}x;
int main(){
	int N,t;scanf("%d",&N);
	char i[2];
	while (N--){
		scanf("%s",i);
		if (i[0]=='A'){
			scanf("%d",&t);
			x.insert(t);
		}
		else{
			printf("%d\n",x.getmax());
			x._delete(1);
		}
	}
}