#include <iostream>
#include <conio.h>

using namespace std;

int count = 1;

void swap(char *s,int i,int j)
{
     char temp = s[i];
     s[i] = s[j];
     s[j] = temp;
//     cout<<"**"<<s<<endl;
 }

void prem(string s,int st,int en)
{
     if(st >=en)
     {
           
           cout<<"count :"<<count<<" "<<s<<endl;
           }
           for(int i=st;i <= en;i++)
           {
                   char temp = s[st];
                   s[st] = s[i];
                   s[i] = temp;
//                    swap(s,st,i);
                    prem(s,st+1,en);
//                    swap(s,st,i);

                   char temp1 = s[st];
                   s[st] = s[i];
                   s[i] = temp1;
                   
                    }
 };

int main()
{
    string s = "abcd";
    prem(s,0,3);
    cout<<getch();
    return 0;
}
