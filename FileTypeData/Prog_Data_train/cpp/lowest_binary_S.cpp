#include <iostream>
#include <conio.h>

using namespace std;


int minElement(int a[],int s,int e)
{
    if(e - s < 2)
    return max(a[0],a[1]);
    if(s > e)
    return a[0];
    int mid = (s+e)/2;
    if(mid < e && a[mid] > a[mid+1])
    return a[mid+1];
    if(a[s] > a[mid])
    return minElement(a,s,mid);
    else
    return minElement(a,mid,e);
    
};

int main()
{
    int a[] = {5, 6, 1, 2, 3, 4};
    cout<<"min element: "<<minElement(a,0,5)<<endl;
    cout<<getch();
    return 0;
}
