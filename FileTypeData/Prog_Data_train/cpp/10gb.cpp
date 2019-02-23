#include<iostream>
#include <stdio.h>
#include <stdlib.h>
#include <time.h>
using namespace std;
int main()
{
    int array[] = {0,0,0,0,0,0,0,0,0,0};
    srand(time(NULL));
    FILE *fp;
    fp=fopen("input10gb.txt","w");
    if(fp == NULL){
        puts("cannot open file");
        fclose(fp);
    }
    long long int t = 0;
    for(t = 0 ; t < 20;t++){
        int random = rand()%1000;
        fputs("s",fp);

    }

    return 0;
}
