#include<iostream>
using namespace std;
int main ()
{
    int num,accual,remainder,copy,doubleremainder;
    
    cin>>accual;
    
    copy=accual;
    num=0;
    while(copy>=1)    
    {
     remainder=copy%10;
     if(remainder!=0)
     {
                     doubleremainder=accual%remainder;
                     if(doubleremainder==0)
                     {
                                          num++;
                                                               
                     }
     }
     copy=copy/10;           
    }
    cout<<"num of divisor  "<<num<<endl;
        
    
    
    
    
    
    system("pause");
    
    
}
