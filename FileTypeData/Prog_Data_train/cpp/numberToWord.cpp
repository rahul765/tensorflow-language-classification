#include <iostream>
#include <conio.h>
#include <stack>

using namespace std;

int findMedian(int a[],int b[],int n)
{
    int i=0,j=0;
    while(i+j+2<=n)
    {
                   if(a[i] < b[j])
                   i++;
                   else
                   j++;
                                      
                   }
                   return (a[i]+b[j])/2;
};
int main()
{
   int ar1[] = {1, 5, 7, 10, 13};
   int ar2[] = {11, 15, 23, 30, 45};
   cout<<findMedian(ar1,ar2,5);
   cout<<getch();
    return 0;
}
