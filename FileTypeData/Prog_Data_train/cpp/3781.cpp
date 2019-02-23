#include <iostream> 
#include<algorithm>
using namespace std; 
int main(){int d[10],t,c;
    int p;cin>>p;while(p--){cin>>c;
    for(t=0;t<10;t++)cin>>d[t];
    sort(d,d+10);cout<<c<<" "<<d[7]<<endl;
    }
        return 0; }
