#include <iostream>
#include <stdlib.h>
#include <time.h>
using namespace std;
int main()
{
    srand (time(NULL));
    int a[50];
    for(int t = 0 ; t < 50 ; t++){
        int random = rand()%2;
        a[t]= random;
    }
    sizeof(a)/sizeof (int);
    cout<<size;


}
