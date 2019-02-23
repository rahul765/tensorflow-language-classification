#include <iostream>
#include <fstream>
#include <string>
 
using namespace std;
 
int main() 
{
        string line;
 
        ifstream myfile ( "exp.txt" );
 
        if ( myfile.is_open() ) {
           while ( myfile.good() ) {
                 getline ( myfile, line );
 
                 if( line[0] == '/' || line[0] == ' ' ) {
 
                 }else
                     cout << line[0] << line[1] << endl;
           }
 
           myfile.close();
       }
 
        else 
             cout << "Unable to open file"; 
 
        cout << "/ the complete ID" << endl;     
 
        system("pause");
 
        return 0;
 
}
