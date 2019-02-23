#include<iostream>
using namespace std;
int main(){
int b,k,i,s,n[9],__,_;cin>>__;
while(__--){b=1;k=0;
for(_=0;_<9;_++)scanf("%x",&n[_]);
  for(i=0;i<32;i++){
  s=0;for(_=0;_<8;_++)s+=n[_]^k;
  if(s<<31-i!=(n[8]^k)<<31-i)k+=b;
  b<<=1;}
printf("%x\n",k);
}
return 0;}
