#include <iostream>
#include <conio.h>
#include <string>

using namespace std;

struct vc
{
       int value;
       int count;
       };

int checkRepeatedNumber(int a[])
{
    int i = 1;
  vc *v = new vc();
  int len = 38;
  v->value = a[0];
  v->count = 1;
  while(i < len ){
  if(v->value != a[i])
  {
              v->count--;
              if(v->count <= 0)
              {
                          v->value = a[i];
                          v->count++;
                          }
                          
              
              }
              else{  
  v->count++;
}
i++;
}
  
  return v->value;  
};


int main()
{
    int a[] = {1,2,2,2,2,2,2,2,2,2,2,2,2,1,1,1,1,1,1,1,1,1,5,5,6,6,6,6,1,1,1,1,1,1,1,1,1};
    cout<<"Repeated Number is "<<checkRepeatedNumber(a)<<endl;
    cout<<getch();
    return 0;
}
