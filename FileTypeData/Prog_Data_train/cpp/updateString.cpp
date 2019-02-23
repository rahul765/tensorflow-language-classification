#include <iostream>
#include <conio.h>

using namespace std;


void transformString(char s[])
{
     int len = strlen(s);
     if(len ==0)
     return;
     bool out = true;
     int i = 0;
     int j = 0;
     while(s[i])
     {
                    if(s[i] == ',' || s[i] == '.' || s[i] == ' '){
                    if(out)
                    {
                    s[j++] = '|';
                    out = false;
                    }                    
                    }
                    else{
                    s[j++] = s[i];     
                    out = true;
                    }
                    
                    i++;
                }
                s[j] = '\0';                

 }

int main()
{
    char s[] = "Hey, I am a rockstart...yeah";
    cout<<"Initial string: "<<s<<endl;
    transformString(s);
    cout<<"Final string: "<<s<<endl;
    cout<<getch();
    return 0;
}


