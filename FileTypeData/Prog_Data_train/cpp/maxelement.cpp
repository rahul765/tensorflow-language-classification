#include<iostream>
#include<vector>
#include<algorithm>
using namespace std;
int main(){
vector <int> v1;
vector <int> v;
int t;
for (int i=0;i<4;i++){
cin>>t;
v.push_back(t);    
}     
for(int i=0;i<4;i++){
cout<<v[i]<<endl;
}    
t=*max_element(v.begin(),v.end());
//sort(v.begin(),v.begin()+4);
/*v1.swap(v);     
for(int i=0;i<4;i++){
cout<<v1[i]<<endl;
}   
*/
for(int i=0;i<4;i++){
cout<<v[i]<<endl;
}   

cout<<"MAX"<<t<<endl;
system("pause");
return 0;
}
