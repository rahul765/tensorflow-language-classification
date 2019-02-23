#include <iostream>
#include <conio.h>

using namespace std;

void getMaxSubArray(int a[],int n)
{
     int sum = 0;
     int gsum = 0;
//     int s=0;
//     int e= 0;
     int i=0;
     while(i<n)
     {
               sum += a[i];
               if(sum < 0)
               sum = 0;
               else
               if(gsum < sum)
               gsum = sum;
               i++;
               }
               cout<<gsum<<endl;
};


int main()
{
    int a[] = {-2, 1, -3, 4, -1, 2, 1, -5, 4};
    getMaxSubArray(a,9);
    cout<<getch();
    return 0;
}
