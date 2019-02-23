#include<bits/stdc++.h>
#include<cmath>
using namespace std;
bool isprime(int in){
	switch (in){ case 1:return 0;case 2:return 1; }
	int limit=(int)sqrt(in);
	for (int _=2;_<=limit;_++)
		if (!(in%_))return 0;
	return 1;
}
int main(){
	int ___,N;cin>>___;while (___--){
		cin>>N;
		for (int _=1;_<=(int)sqrt(N);_++){
			if (!(N%_)&&isprime(N/_)&&isprime(_)){
				puts("Yes");goto nx;}
		}puts("No");
	nx:;
	}
}