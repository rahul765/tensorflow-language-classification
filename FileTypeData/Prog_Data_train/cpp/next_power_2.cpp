#include <iostream>
#include <conio.h>
#include <stack>

using namespace std;

int nextPowerofTwo(int n)
{
    if(n == 1)
    return 2;
    int x = n & (n-1);
    x = x<<1;
    return x;
}

int main()
{
    int n = 2;
    cout<<!n;
    //cout<<nextPowerofTwo(n);
    cout<<getch();
    return 0;
}
