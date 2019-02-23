#include <iostream>
#include <conio.h>

using namespace std;

int getLargestValueInRight(int a[],int n,int selectedIndex)
{
    if(n == 0)
    return 0;
    int *largest = new int(n);
    largest[0] = INT_MAX;
    largest[1] = a[0];
    for(int i=2;i<n;i++)
    {
            largest[i] = max(a[i-1],largest[i-1]);
            }
            if(largest[selectedIndex] > a[selectedIndex])
            return largest[selectedIndex];
            else
            return 0;
}

int main()
{
    int a[] = {1,4,67,8,2,6,9,52,77};
    cout<<getLargestValueInRight(a,9,5);
    cout<<getch();
    return 0;
}
