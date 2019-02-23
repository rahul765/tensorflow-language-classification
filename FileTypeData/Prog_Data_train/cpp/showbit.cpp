#include<iostream>
#include<stdio.h>
using namespace std;

void showbit(int k)
{
        int t=1 ;
        
        while(t<256){
                if(k&t)
                        cout<<1;
                else
                        cout<<0;
                t<<=1;
                cout<<"\n"<<"T "<<t<<"\n";
        }

}
int main()
{
    
    for(int t = 0 ; t<259 ; t++){
        char k = t;
        if(t>=254 && t < 269){
                showbit(t);
        }
        printf("%4d  \n",k);
    }
    
    
    return 0;
}

