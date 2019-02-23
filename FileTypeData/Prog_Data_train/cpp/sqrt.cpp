#include <iostream>
#include <conio.h>

using namespace std;

double sqrt(double n,double l,double r)
{
    double mid = (double)(l+r)/(double)2;
    if(mid == n/mid)
    return mid;
    if(mid > n/mid)
    return sqrt(n,l,mid);
    else
    return sqrt(n,mid,r);
};
//
int main()
{
    char s = 'a'+23;
    cout<<s<<endl;
    //cout<<sqrt(4,0,4);
    cout<<getch();
    return 0;
}
