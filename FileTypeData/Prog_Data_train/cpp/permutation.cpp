http://www.geeksforgeeks.org/archives/767
#include<stdio.h>
#include<string.h>
void swap(char *a,char *b)
{
    char temp;
    temp = *a;
    *a = *b;
    *b = temp;
}
void permutation ( char *a , int i , int n )
{

    if( i == n )
        printf( " %s:",a);
    else{
        for( int j = i ; j <= n ; j++ ){
            swap(a+i ,a+j);
            permutation( a , i + 1 , n );
            swap( a + i , a + j );
        }
    }
}
int main()
{
    char a[] ="ABC";
    permutation( a , 0 ,2 );
    return 0;
}
