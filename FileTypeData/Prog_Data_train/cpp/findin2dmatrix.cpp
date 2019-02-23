#include <iostream>
#include <conio.h>

using namespace std;

bool findValueInMatrix(int a[4][4],int n,int m,int value)
{
     int row = 0;
     int col = m-1;
     if(a[0][0] > value || value > a[n-1][m-1]) return false;
     
     while(row < n || col >= 0){
     if(a[row][col] > value)
     col--;
     else if(a[row][col] < value)
     row++;
     else{
     cout<<a[row][col];
     return true;}
     }
     return false;
 };

 
// driver program to test above function
int main()
{
  int mat[4][4] = { {10, 20, 30, 40},
                    {15, 25, 35, 45},
                    {27, 29, 37, 48},
                    {32, 33, 39, 50},
                  };
  findValueInMatrix(mat, 4,4, 27);
  getchar();
  return 0;
}
