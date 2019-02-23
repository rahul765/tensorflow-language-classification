#include<iostream>
//#include<stdio.h>
//#include<stdlib.h>
using namespace std;
int main()
{
/*int  i;
char s[256];
cout<<"enter the no."<<endl;
fgets(s,256,stdin);
i=atoi(s);

cout<<i*2<<endl;     
system("pause");
return 0;
}
*/
int i;
char a[33];
cin>>i;
itoa(i,a,10);
cout<<a<<endl;
cout<<a[2]<<endl;
system("pause"); 
return 0;
}
