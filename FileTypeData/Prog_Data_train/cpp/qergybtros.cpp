#include <iostream>
#include <conio.h>
#include <algorithm>
#include <map>
#include <algorithm>
#include <vector>
#include <set>


using namespace std;

void sortByFrequency(int a[],int n)
{
     if(n<=2)
     return;
     map<int,int> m;
     map<int,int>::iterator it;
     for(int i=0;i<n;i++){
            it =  m.find(a[i]);
     if(it->first){
     int n = m[a[i]];
     m[a[i]] = n+1; 
     }
     else
     m[a[i]]= 1;
     }

     for(it = m.begin();it!=m.end();it++)
     cout<<it->first<<" "<<it->second<<endl;
     
     //
     };


void printArray(int a[],int n)
{
  for(int i=0;i<n;i++)
  cout<<a[i]<<" ";   
 }

int main()
{
    int a[] =  {2, 5, 2, 8, 5, 6, 8, 8};
    sortByFrequency(a,8);    
    printArray(a,8);
    cout<<getch();
    return 0;
}
