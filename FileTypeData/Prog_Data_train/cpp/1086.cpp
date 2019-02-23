#include<iostream>
#include<string>
#include<algorithm>
using namespace std;
unsigned int cs=0,add=0,t,f,cache[20000],N,M,p;
hash<string>w;string in;
int main(){
	fill(cache,cache+20000,0);
	cin>>N>>M;
	while (N--&&cin>>in){
			f=w(in);for (p=cs;p<cs+M;p++){
				if (cache[p]==f){
					t=cache[p];move(cache+p+1,cache+add,cache+p);cache[add-1]=t;
					puts("Cache"); goto nx;
				}
			}puts("Internet");cache[add++]=f;if (add-cs>M)cs++;
		nx:;
		//	cout<<f<<endl;
	}
}