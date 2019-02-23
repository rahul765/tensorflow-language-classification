#include <iostream>
#include <conio.h>
#include <stack>

using namespace std;

int getMaxArea(int hist[],int n)
{
    int max_area = 0;
    if(n == 0)
    return 0;
    
    stack<int> s;
    int i=0;
    while(i < n){
    if(s.empty() || hist[i] > s.top())
    s.push(hist[i++]);
    else
    {
        int count = 0;
        while(!s.empty() && s.top() > hist[i])
        {
                      int top = s.top();
                      s.pop();
                      count++;
                      max_area = max(max_area,top*count);
                      }
                      for(int k=count;k>=0;k--)
                      s.push(hist[i]);
                      i++;        
    }

}
return max_area;
};


// Driver program to test above function
int main()
{
    int hist[] = {6, 2, 5, 4, 5, 1, 6};
    int n = sizeof(hist)/sizeof(hist[0]);
    cout << "Maximum area is " << getMaxArea(hist, n);
    cout<<getch();
    return 0;
}
