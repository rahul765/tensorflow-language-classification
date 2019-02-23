#include <iostream>
#include <conio.h>
#include <string>

using namespace std;


bool isValidIP(char s[])
{
     int count = 0;
     char * p = strtok(s,".");
     while(p != NULL)
     {
             
             if(!atoi(p))
             return false;
             int a = atoi(p);
             if(a < 1 || a > 255)
             return false;
             count++;
             p = strtok(NULL,".");
             }
             if(count!= 4)
             return false;
     return true;
 }

int main()
{
    char s[] = "1sameer1";
    if(isValidIP(s))
    cout<<"this is a valid ip"<<endl;
    else 
    cout<<"this is an invalid ip"<<endl;
           cout<<getch();
    
    return 0;
}
