#include <iostream>
#include <conio.h>

using namespace std;


int countwords(char a[])
{
    int count = 0;
    int len = strlen(a);
    int i=0;
    //define state IN and OUT
    bool OUT = true;
    while(i<len)
    {
                if(a[i] == ' ' || a[i] == '\\' || a[i] == ',')
                {
                  if(!OUT){
                  count++;
                  OUT = true;}      
                }
                else
                {
                    OUT = false;
                }
                i++;
                }
      return count;
}

int main()
{
    char a[] = "sameer   is a good boy";
    cout<<countwords(a);
    cout<<getch();
    return 0;
}
