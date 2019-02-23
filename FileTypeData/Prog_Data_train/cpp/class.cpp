//public's function access the data of private but outside function do not access the data of private but esaily access the data of public
//if the push function  is private and access by the main than the error will come
 
#include<iostream>
using namespace std;
class pa
{
      private:
              int priv;              
                  
             //int push(int &x)
      public:   
             
             int push(int &x); 
             
};
int  pa::push(int &x)
{
     int m;
     m=push(x);
     return x; 
}
int main ()
{
         pa d1,d2;
         int x,r=0;
         cin>>x;
         r=d1.push(x);
         cout<<r<<endl;
         system("pause");
}
