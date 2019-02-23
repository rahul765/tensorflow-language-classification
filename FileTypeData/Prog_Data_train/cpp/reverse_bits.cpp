#include <iostream>
#include <conio.h>

using namespace std;

int main()
{
    int a = 18;
    int i=0;
    while(1<<i <= a)
    {
                       a = a ^ 1<<i;
                       i++;
               }
               cout<<a<<endl;
    cout<<getch();
    return 0;
}
