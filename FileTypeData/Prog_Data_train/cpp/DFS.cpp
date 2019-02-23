#include <iostream>
#include <conio.h>
#include <list>

using namespace std;

list<int> adj[4];
bool visit[4] = {false};

void addEdge(int x,int y)
{
     adj[x].push_back(y);
 }
 
 void DFS(int source)
 {
  visit[source] = true;    
  cout<<source<<endl;    
  list<int>::iterator t;
  for(t = adj[source].begin();t!= adj[source].end();t++)
  {
        if(!visit[*t])
        {
                          DFS(*t);
                          }
        }    
  }
 
int main()
{
    addEdge(2,0);
    addEdge(0,2);
    addEdge(0,1);
    addEdge(1,2);
    addEdge(2,3);
    
    DFS(2);
    cout<<getch();
    return 0;
}
