#include <iostream>
#include <conio.h>

using namespace std;


void printWithoutLoop(int n,int i)
{
                     if(i >= n)
                     return;
                     cout<<i<<"  ";
                     printWithoutLoop(n,i+1);
                     }

int main()
{
    printWithoutLoop(100,1);
    cout<<getch();
    return 0;
}
