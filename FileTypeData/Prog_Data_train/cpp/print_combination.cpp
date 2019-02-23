#include <iostream>
#include <conio.h>
#include <stack>

using namespace std;


void printString(char *s,int len,int *a,int count,bool t)
{
     if(len <1)
     {
            for(int i =0;i<count;i++)
            cout<<a[i]<<endl;
     return;}
     
     a[count++] = (int)(s[len]-48)+(int)(s[len - 1]-48)*10;
     printString(s,len-2,a,count,true);
     
     
 
     
 };
 
 
void printWord(char *s)
{
    int a[100]; 
    int count = 0;
    int len = strlen(s)-1;
    printString(s,len,a,count,true);
 };


int main()
{
    char *a = "1234";
    printWord(a);
    cout<<getch();
    return 0;
}
