#include <iostream>
#include <conio.h>

using namespace std;

void cPair(int n)
{
     int sum[n];
     int i=0;
     int j = 0;
     sum[0] = 0;
     for(int i=1;i<n;i++){
     sum[i] = 0;
     sum[i] = sum[i-1]+i;}
     i= 0;
     while(j<n && i<n)
     {
//          cout<<"i "<<i<<" j"<<j<<endl;     
//          cout<<"sum[j] "<<sum[j]<<endl;
//          cout<<"sum[i] "<<sum[i]<<endl;
 //         cout<<"sum[j]-sum[i] "<<sum[j]-sum[i]<<endl;
          if(sum[j]-sum[i] == n){
          cout<<j<<" "<<i<<endl;
          i++;
          j++;}
          else if((sum[j] - sum[i]) > n)
          i++;
          else
          j++;       
                }
     
 };

int main()
{
    int n = 15;
    cPair(n);
    cout<<getch();
    return 0;
    }
