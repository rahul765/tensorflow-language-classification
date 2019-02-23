#include <iostream>
#include <conio.h>

using namespace std;

int getNum(int digit)
{
    int output = 1;
    if(digit == 1)
    return 10;
    while(digit > 0)
    {
                output *= 10;
                digit--; 
                }
                return 9*output;
};

int digitX(int n)
{
    if(n < 0)
    return -1;
    
    int digit = 1;
    int numbers = 0;
    numbers = getNum(digit);
    if(numbers > n)
    return getIndexofNum(n,digit);
    else
    {
        n = n-numbers;
        digit++;
    }
    return -1;
};

int main()
{
    int n = 1001;
    cout<<digitX(n)<<endl;
    cout<<getch();
    return 0;
}
