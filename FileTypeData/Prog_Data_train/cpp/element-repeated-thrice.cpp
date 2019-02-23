#include <iostream>
#include <stdlib.h>
#include <time.h>
using namespace std;
int main()
{
    srand (time(NULL));
    int a[7] = {3,3,5,5,3,4,4};
    int sum =a[0];
    for(int t = 1 ; t < 7 ; t++){
            sum = sum ^ a[t];
            cout<<sum<<endl;
    }
    //cout<<sum<<endl;


}


