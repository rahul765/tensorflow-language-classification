#include <iostream>
#include <conio.h>
#include <queue>
#include <set>

using namespace std;

struct event
{
       int left;
       int right;
       int height;
       bool isInsert;
       };

void getSkyLine(int a[8][3])
{
     std::priority_queue<int, std::vector<int>, std::greater<int> > my_min_heap;
     set<int> s;
     
     
 }

int main()
{
    int a[8][3] = {{1,11,5},
                   {2,6,7},
                   {3,13,9},
                   {12,7,16},
                   {14,3,25},
                   {19,18,22},
                   {23,13,29},
                   {24,4,28}};
//    getSkyLine(a);
    cout<<getch();
    return 0;
}
