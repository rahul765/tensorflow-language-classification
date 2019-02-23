#include <iostream>
#include <conio.h>

using namespace std;

void maxArraySum(int arr[],int n,int *st,int *en)
{
     int max = 0;
     int sum = 0;
     int j = 0; //start index;
     int last_index = 0;
     for(int i=0;i<n;i++)
     {
             sum += arr[i];
             if(sum <= 0){ sum = 0;
             j = i+1;}
             if(sum > max){
             max = sum;
             last_index = i;
             }
             
             }
             *st = j;
             *en = last_index;
             cout<<"st "<<j<<endl;
             cout<<"en "<<last_index<<endl;
             
             cout<<"max sum: "<<max<<endl;
};


int main()
{
    int arr[] =  {1, -2, 3, 10, -4, 7, 2, -5};
    int st;
    int en;
    int n = 8;
    maxArraySum(arr,n,&st,&en);
    for(int i=st;i<=en;i++)
    cout<<arr[i]<<" ";
    cout<<endl;
    cout<<getch();
    return 0;
}
