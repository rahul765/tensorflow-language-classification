#include<cmath>
#include<iostream>
using namespace std;
int main(){
double p,n;//k^n=p --> k=p^(1/n)
while(~scanf("%lf%lf",&n,&p))printf("%.0lf\n",(pow(p,1/n)));
return 0;}
