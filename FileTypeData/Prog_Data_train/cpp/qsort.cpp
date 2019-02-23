#include <iostream>
#include <conio.h>

using namespace std;

int compare(const void *a,const void *b)
{
     return ( *(char*)a - *(char*)b );
     
 }

void removeDuplicate(char a[])
{
     int len = strlen(a);
     int s = 0;
     //first implement quicksort for string
     qsort(a,13,sizeof(char),compare);
     cout<<"sorted "<<a<<endl;
     
};

int main()
{
    char a[] = "geeksforgeeks";
    removeDuplicate(a);
    cout<<a;
    cout<<getch();
}
