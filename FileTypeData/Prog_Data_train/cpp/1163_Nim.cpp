#include<iostream>
#include<cstdio>
using namespace std;
int r=0,t;
int main(){
	int N;cin>>N;while (N--&&scanf("%d",&t))r^=t;
	puts(r?"Alice":"Bob");
}