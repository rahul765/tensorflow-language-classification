#include <iostream>
#include <utility>
#include <string>
#include <map>
using namespace std;

int main () {
  pair <int,int> one;
  pair <int,int> two;
    map<string,int> mymap;
  one = make_pair (10,20);
  two = make_pair (10.5,'A'); // ok: implicit conversion from pair<double,char>
  mymap.insert ( pair<string,int>("fo",100) );

  //cout << "one: " << one.first << ", " << one.second << "\n";
  //cout << "two: " << two.first << ", " << two.second << "\n";
  string a= "foafodfodfdodf";
  a =""+  a.substr(1,1)+a.substr(1,1);
    mymap.insert ( pair<string,int>(a,200) );
  cout<<a<<endl;
  return 0;
}
