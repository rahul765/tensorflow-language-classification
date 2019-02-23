#include<bits/stdc++.h>
using namespace std;
#include<gcjio>
//GCJ Qualification Africa 2010
int main(){
	setio();
	int T,i,e;char in;
	cin>>T;getchar();
	for (int __=1;__<=T;__++){
		char x[200][20];i=0;e=0;
		while ((in=getchar())!='\n'){
			if (in==' ')x[i++][e]='\0',e=0;
			else x[i][e++]=in;
		}
		x[i][e]='\0';
		printf("Case #%d: ",__);
		for (int _=i;~_;_--){
			printf("%s ",x[_]);
		}putchar('\n');
	}
}