#include<iostream>
#include<new>
using namespace std;
#define f(i,a,b) for( i = a ; i < b ; i++)
int * fn()
{
    int *a =new int [4];
    int i;

    int * temp = a;
    f(i,0,4){
        *a =1;
         a++;

    }
    return temp;
}
int main()
{
    int i;

    int * temp = fn();
    f(i,0,4){
        cout<<*temp;
        temp++;
    }

    return 0;
}
