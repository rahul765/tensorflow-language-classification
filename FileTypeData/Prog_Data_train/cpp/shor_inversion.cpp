#include <iostream>
#include <conio.h>

using namespace std;

void showInversions(int a[],int n)
{
     for(int i=0;i<n-1;i++)
     {
             for(int j=i+1;j<n;j++)
             {
                     if(a[i] > a[j])
                     cout<<"pair: "<<a[i]<<" , "<<a[j]<<endl;
                     }
             }
 }

int  main()
{
     int a[] = {2, 4, 1, 3, 5};
     showInversions(a,5);
     cout<<getch();
     return 0;
     }
