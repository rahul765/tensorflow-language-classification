#include<iostream>
#include<vector>
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
sort(v.begin(),v.begin()+4);
/*v1.swap(v);     
for(int i=0;i<4;i++){
cout<<v1[i]<<endl;
}   
*/
for(int i=0;i<4;i++){
cout<<v[i]<<endl;
}   


system("pause");
return 0;
}
