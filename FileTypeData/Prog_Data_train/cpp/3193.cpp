#include<iostream>
using namespace std;
#define SIZE 1000000
#define CHARSET 56
int trie[SIZE][CHARSET]={0},e=2,children[SIZE]={0};
inline int h(char&q){
	if (q>64)return q>96?q-71:q-65;
	switch (q){
	case ' ':return 52; case ',':return 53;
	case '.':return 54; case '?':return 55;
	}
}
char i[61];int M,N,_,a=0;
int main(){
	scanf("%d%d",&M,&N);getchar();
	int p;
	while (M--){
		gets(i);p=1;//p: the node we are visiting
		for (int _=0;_<strlen(i);++_){
			if (trie[p][h(i[_])]){
				//found
				p=trie[p][h(i[_])];
				children[p]++;
			}
			else{
				trie[p][h(i[_])]=e;
				children[p=e++]++;
			}
		}
	}
	while (N--){
		p=1;gets(i);
		for (int _=0;_<strlen(i);++_){
			if (trie[p][h(i[_])])
				p=trie[p][h(i[_])];
			else{
				goto nc;
			}
		}++a;
	nc:;
	}
	cout<<a;
}