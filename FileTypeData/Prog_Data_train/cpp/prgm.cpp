#include <iostream>
#include <conio.h>

using namespace std;



int main()
{
    int n = 7;
    int i=0;
    while((n & 1<<i)){
             n = n ^ 1<<i;
             i++;
             }
             n = n | 1<<i;
    cout<<"new n "<<n;         
    
    cout<<getch();
    return 0;
}
