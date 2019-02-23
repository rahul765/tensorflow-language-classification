#include <iostream>
#include <conio.h>

using namespace std;


int morethank(int a[],int  n,int k)
{
    int count = 1;
    int num = a[0];
    int max = n/k;
    int i = 1;
    while(i<n){
    if(count >=  max)
    return num;
    if(num == a[i])
    count++;
    else
    {
        if(count == 0)
        {
                 count = 1;
                 num = a[i];
                 }
                 else
                 count--;
    }
    i++;
    }
    return -1;
    
    
};

int main()
{
    int a[] = {4,5,6,7,8,4,4};
    int n = 7;
    int k = 3;
    cout<<"Number greater than n/k "<<morethank(a,n,k);
    cout<<getch();
    return 0;
}
