#include <iostream>
#include <conio.h>

using namespace std;

int knapSack(int a[],int total,int n)
{
    if(n==0 || total== 0)
    return 0;
    if(a[n] > total)
    return knapSack(a,total,n-1);
    else
    return max(knapSack(a,total,n-1),a[n]+knapSack(a,total-a[n],n-1));
} 

void splitInTwo(int a[],int n)
{
     int total = 0;
     for(int i=0;i<n;i++)
     total +=a[i];
     int knapsackWeight = total/2;
     cout<<knapSack(a,knapsackWeight,n-1);

 };
 

int main()
{
    int a[] = {3, 4, 5, -3, 100, 1, 89, 54, 23, 20};
    splitInTwo(a,10);
    cout<<getch();
    return 0;
}
