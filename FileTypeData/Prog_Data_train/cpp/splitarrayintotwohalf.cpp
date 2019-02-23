#include<stdio.h>
void swap(int *a,int *b)
{
    printf(" a: %d  b: %d \n",*a,*b);
    int temp=*a;
    *a = *b; *b = temp;
    printf("afterm swap a: %d  b: %d\n",*a,*b);
}

int main()
{
    int a[6]={100,99,8,3,7,1};
    for(int k=0;k<sizeof(a)/sizeof(int);k++)
        printf("%d ",a[k]);
    int swapflag = 0;
    int firsthalfsum = 0;
    int secondhalfsum = 0;
    for(int t = 0 ; t*2 < sizeof(a)/sizeof(int)-1 ; t++){    //iterate half array
        if(swapflag==1){

            swap(&a[t],&a[sizeof(a)/sizeof(int)-t]);
            firsthalfsum  += a[t-1];
            secondhalfsum += a[sizeof(a)/sizeof(int)-t];
        }else{
            firsthalfsum += a[t];
        }
        if(firsthalfsum >= secondhalfsum)
            swapflag=!swapflag;
    }
    printf("\n");
    for(int k=0;k<sizeof(a)/sizeof(int);k++)
        printf("%d ",a[k]);
}
