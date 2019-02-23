#include<iostream>
#include<bitset>
using namespace std;
int main(){
int r,x,y;scanf("%x,%d,%d",&r,&x,&y);
bitset<32> _(r);
_[x]=0;_[y]=1;_[y-1]=1;_[y-2]=0;
printf("%x\n",_.to_ulong());return 0;
}
