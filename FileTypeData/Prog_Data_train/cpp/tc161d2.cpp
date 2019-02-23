#include<string>
#include<iostream>
#include<vector>
using namespace std;
int main()
{
    vector<string> v;
    string st;
    cin>>st;

    string train = v[0];
    int index=-1;
    for(int t = 1 ; t < v.size();t ++){
        for(int x = 1 ; x< train.size() ; x++){
            if(train[x]== v[t][index+1]){
                index++;
            }else{
                index = -1;
            }
        }
        if(index>-1){
            train =train+v[t].substr(index+1,v[t].size()-index-1);
        }
    }
    string output="";int flag ;
    for(int x = 0 ; x < train.size() ; x++){
        flag = 0;
        for(int t = x+1 ; t< train.size();t ++){
            if(train[x]==train[t]){
                flag == 1;
                break;
            }
        }
        if(flag == 0){
                output += train[x];
        }
    }
}
