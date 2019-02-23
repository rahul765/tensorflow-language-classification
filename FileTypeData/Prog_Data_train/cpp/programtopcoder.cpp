#include<iostream>
#include<string>
using namespace  std;
int main ()
{
   int t;
   int x,P;
   string a;
   int shift ;
   cin>>a;
   t=a.size(); 
   cin>>shift;
   for(int i=0 ; i<t ; i++)
   {
           x=a[i];       
           if(x-shift<65)
           {
          P=65-(x-shift);              
           a[i]='Z'-P+1;
           }
           else
               {
               a[i]=a[i]-shift;    
               }
   }
   cout<<"array"<<endl;   
   cout<<a<<endl;
   cin>>t;  

}
