#include <iostream>
#include <conio.h>

using namespace std;

int search(int mat[4][4],int n,int s)
{
    int x = n;
    int y = 0;
    while(x >=0 && y <n){
    if(mat[y][x] > s)
    x--;
    else if(mat[y][x] < s)
    y++;
    else
    return mat[y][x];
}
return -1;
};


int main()
{
  int mat[4][4] = { {10, 20, 30, 40},
                    {15, 25, 35, 45},
                    {27, 29, 37, 48},
                    {32, 33, 39, 50},
                  };
  cout<<search(mat, 4, 29);
  cout<<getch();
  return 0;
}


