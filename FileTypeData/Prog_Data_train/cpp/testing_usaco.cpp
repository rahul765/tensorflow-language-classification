#include <iostream>
#include <conio.h>

using namespace std;


bool isPalin(int n)
{
int len = 0;
cout<<len<<endl;
int x = 1;
while(n/x > 0){
x *=10;
len++;}
int i=0;
int j = len-1;
while(j > i)
{
int num1 = 1;
int k =0;
while(j>=k)
k++;
num1 *=10;

int num2 = 1;
k=0;
while(i>=k){
k++;
num2 *=10;}
cout<<"num1 "<<num1<<" mun2 "<<num2<<endl;
if(num1 != num2)
return false;

}
return true;
}

int main()
{
    cout<<"output: "<<isPalin(11)<<endl;
    cout<<getch();
    return 0;
}
