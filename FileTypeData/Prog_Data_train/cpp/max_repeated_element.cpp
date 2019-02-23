#include <iostream>
#include <conio.h>

using namespace std;

int getMaxRepeatedElement(int a[],int n)
{
     if(n == 0)
     return -1;
     for(int i=0;i<n;i++)
     a[a[i]] = a[a[i]%n]+n;
     
     int max = 0;
     int k = 0;
     for(int i=0;i<n;i++)
     if(max < a[i]){
     max = a[i];
     k = i;}
     
     return k;
 };

int main()
{
    int a[] = {3,2,3,4,5,5,5,5,6,7};
    int n = 10;
    cout<<getMaxRepeatedElement(a,n);
    cout<<getch();
    return 0;
}
