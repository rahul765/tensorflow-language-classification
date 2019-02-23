#include<iostream>
using namespace std;
int main ()
{
int x;
x=5;
for(int i=0 ; i<5; i++)
{
for(int j=0 ; j<4; j++)
{
if(i==j)
break;
cout<<x;        
}
cout<<endl;
}    
system("pause");   
return 0;
    
    
    
}
