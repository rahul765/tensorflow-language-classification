#include <iostream>
#include <conio.h>

using namespace std;


double atoi(char *s)
{
       double n = 0;
       int i=0;
       bool isNegative = false;
       while(s[i] != '\0')
       {
                  if(s[i] == '-')
                  isNegative = true;
                  else
                  n = n*10 + (double)(s[i] - '0');
                  
                  }
       if(isNegative)
       n*=-1;
       
       return n;
       }

int main()
{
    char *s = "1234";
    cout<<atoi(s);
    cout<<getch();
    return 0;
};
