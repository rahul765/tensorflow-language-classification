#include <iostream>
#include <conio.h>
#include <queue>

using namespace std;

int main()
{
    priority_queue<int,std::vector<int>,std::greater<int> > s;
    s.push(5);
        s.push(2);
            s.push(50);
                s.push(25);
                    s.push(15);
                        s.push(500);
                        while(s.size() > 0){
                        cout<<s.top()<<endl;
                        s.pop();}
    cout<<getch();
    return 0;
}
