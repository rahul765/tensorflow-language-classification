#include <iostream>
#include <conio.h>

using namespace std;

//only allowed operation is flip
//flip array from 0 to n
void flip(int arr[],int n)
{
     int s = 0;
     while(s<n)
     {
               int temp = arr[s];
               arr[s] = arr[n];
               arr[n] = temp;
               s++;
               n--;
               }
 }

//max index from 0 to n
//find the max index just like selection sort
int findMaxIndex(int arr[],int n)
{
    while
}
void pancakeSorting(int arr[],int n)
{
     
 }


int main()
{
    int a[] = {1,4,2,3,8,7,5,6};
    pancakeSorting(a,8);
    cout<<getch();
    return 0;
}
