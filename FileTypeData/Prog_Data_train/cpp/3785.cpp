#include <iostream> 
#include<algorithm>
#include<string>
using namespace std; 
int main(){ 
    string s;
    int w,p;cin>>p;
    while(p--){
    cin>>w>>s;
    if(next_permutation(&s[0],&s[0]+s.length()))
        cout<<w<<" "<<s<<endl;else printf("%d BIGGEST\n",w);
    }
        return 0; }
