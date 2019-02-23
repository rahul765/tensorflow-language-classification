//in this function we pass vector as call by  reference 
#include<iostream>
#include<vector>
using namespace std;
vector <int> push(vector <int> &v1)
{

v1.push_back(90);
 
 return v1;
}

    

int main()
{
           vector <int > v2 ,v;
           int x;
           for(int i=0 ; i<4 ; i++ )
           {
                       cin>>x;        
                       v.push_back(x);        
           }    
           v2=push(v);
           cout<<"vectorv1"<<endl;
           cout<<v2[4]<<endl; 

           system("pause");
           return 0;
}
