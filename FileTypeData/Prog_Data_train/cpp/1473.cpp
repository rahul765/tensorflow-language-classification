#include<iostream>
#include<cmath>
#include<string>
using namespace std;
int main(){int __=1;
const double _=1.41421356237;
string s;double m,x,y;
 while(cin>>s){
	if(s=="END")return 0;x=y=0;
	while(1){m=0;
	while(s[0]<='9'&&s[0]>='0'){m=m*10+s[0]-0x30;s=s.substr(1);}
    //nw,
	switch(s[0]){
		case 'E':x+=m;s=s.substr(1);break;
        case 'W':x-=m;s=s.substr(1);break;
		case 'S':if(s[1]=='E'){x+=m/_;y-=m/_;s=s.substr(2);break;}
                 if(s[1]=='W'){x-=m/_;y-=m/_;s=s.substr(2);break;}
                 y-=m;s=s.substr(1);break;
		case 'N':if(s[1]=='E'){x+=m/_;y+=m/_;s=s.substr(2);break;}
                 if(s[1]=='W'){x-=m/_;y+=m/_;s=s.substr(2);break;}
                 y+=m;s=s.substr(1);break;
	}
    //, or .
	if(s[0]=='.')break;s=s.substr(1);
    //123nw
	}
printf("Map #%d\nThe treasure is located at (%.3lf,%.3lf).\nThe distance to the treasure is %.3lf.\n\n",__++,x,y,sqrt(x*x+y*y));
 }
}
