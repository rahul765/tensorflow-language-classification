#include <iostream>
#include <list>
#include <vector>
#include <algorithm>
using namespace std;

int main ()
{
  list<int> mylist;
  list<int>::iterator it;

  // set some initial values:
  for (int i=1; i<=5; i++) mylist.push_back(i); // 1 2 3 4 5

                                                // 1 10 20 30 30 20 2 3 4 5
                                           //               ^
  do {
    for (it=mylist.begin(); it!=mylist.end(); it++)
        cout << " " << *it;
    cout<<endl;
  } while ( next_permutation(mylist.begin() ,mylist.end()) );


  return 0;
}
