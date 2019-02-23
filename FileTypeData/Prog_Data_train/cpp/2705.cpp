#include<iostream>
#include<list>
#define PII pair<int,int>
using namespace std;
int used,L,a,b,df;list<PII>w;
bool shoulddel(PII&i){
	if (b==i.first){used-=i.second;return 1;}
	return 0;
}
int main(){
	int _=0;char s[2];
	while (scanf("%d",&L)&&~L){
		used=0;
		while (scanf("%s",s)){
			if (s[0]=='E'){
				printf("PROBLEM %d:",++_);
				while (!w.empty()){ printf(" %d",w.front().first);w.pop_front(); }
				putchar('\n');break;
			}
			else if (s[0]=='A'){
				scanf("%d%d",&a,&b);
				w.push_front(make_pair(a,b));used+=b;
				while (used>L)used-=w.back().second,w.pop_back();
			}
			else {
				scanf("%d",&b);w.remove_if(shoulddel);
			}
		}
	}
}