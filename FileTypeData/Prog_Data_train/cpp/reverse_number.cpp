#include <iostream>
#include <conio.h>

using namespace std;

int ReverseNo(int number)
{
    int output = 0;
    while(number > 0)
    {
                 int num = number%10;
                 number /=10;
                 output = (output*10+num);
                 
                 }
                 return output;
};

int main()
{
    int a = 4562;
    int b = ReverseNo(a);
    cout<<b<<endl;
    cout<<getch();
    return 0;
}
