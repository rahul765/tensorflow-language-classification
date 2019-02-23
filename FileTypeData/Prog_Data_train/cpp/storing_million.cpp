#include <iostream>
#include <conio.h>
#define BITSPERWORD 32
#define SHIFT 5
#define MASK 0x1F
#define N 10000000

using namespace std;

int a[1 + N/BITSPERWORD];

void set(int i) {        a[i>>SHIFT] |=  (1<<(i & MASK)); }
void clr(int i) {        a[i>>SHIFT] &= ~(1<<(i & MASK)); }
int  test(int i){ return a[i>>SHIFT] &   (1<<(i & MASK)); }


int main()
{
    for(int i=1;i<1000;i++)
    set(i);
    
    for(int i=1;i<1000;i++)
    cout<<test(1)<<endl;
    cout<<test(1000)<<endl;
    cout<<getch();
}
