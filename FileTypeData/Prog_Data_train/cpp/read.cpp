#include <iostream>
#include <fstream>
#include <string>

using namespace std;

int main(){
    string line;
    ifstream inputFile("demo.txt");
    if(inputFile.is_open()){
        while(getline(inputFile, line)){
            cout<<line<<endl;
        }
        inputFile.close();
    } else {
        cout<<"Error reading the file."<<endl;
    }
    return 0;
}