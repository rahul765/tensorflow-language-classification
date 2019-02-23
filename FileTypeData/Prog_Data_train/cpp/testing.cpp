#include <iostream>
#include <conio.h>

using namespace std;

int getpeak(int a[],int n)
{
    if(n == 0 || n==1)
    return n;
    int len = 1;
    int i=1;
    bool toggle = false;
    int max = 0;
    while(i< n)
    {
           if(toggle)
             {
                       if(a[i-1] > a[i])
                       len++;
                       else
                       toggle = false;
                       }
                       else{
             if(a[i-1] < a[i])
             len++;
             else{
                  if(!toggle)
                  toggle = true;
                  else{
                  if(max < len)
                  max = len;
                  else
                  len = 1;}}}
                  i++;
             }
             return max;
};


int main()
{
    int a[] = {12, 4, 78, 90, 45, 23};
    cout<<"getpeak "<<getpeak(a,6);
    cout<<getch();
    return 0;
}
