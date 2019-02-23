#include <iostream>
#include <conio.h>
#include <queue>


using namespace std;


int pivotedBinarySearch(int a[],int arr_size,int no)
{
    int getPivot = pivotedIndex(a,0,arr_size - 1);
    if(getPivot == -1)
    do normal binary search
    if(a[getPivot] == no)
    return getPivot;
    if(a[getPivot] < no)
    binary search in left half
    else
    binary search in right half
    
    
    return 0;
};

int pivotedIndex(int arr[],int beg,int end)
{
    if(beg > end) return -1;
    int mid = (low+high)/2;
    if(a[mid+1] < a[mid])
    return a[mid];
    if(a[low] > a[mid])
    pivotIndex(arr,low,mid-1);
    else
    pivotIndex(arr,mid+1,end);
    
};

int main()
{
int arr[] = {3, 4, 5, 1, 2};
   int arr_size = sizeof(arr)/sizeof(arr[0]);
   int no = 3;
   cout<<pivotedIndex(arr,0,4)<<endl;
   printf("Index of the element is %d", pivotedBinarySearch(arr, arr_size, no));
   cout<<getch();
   return 0;
}
