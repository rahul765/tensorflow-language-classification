#include<iostream>
#include<set>
using namespace std;
multiset<int>w;
int main(){
	int N,t;cin>>N;
	char i[2];
	while (N--){
		scanf("%s",i);
		if (i[0]=='A'){
			scanf("%d",&t);
			w.insert(-t);
		}
		else{
			printf("%d\n",-*w.begin());
			w.erase(w.begin());
		}
	}
}
