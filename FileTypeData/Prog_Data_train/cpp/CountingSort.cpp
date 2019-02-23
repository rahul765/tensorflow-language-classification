#include "CommonInclude.h"

#if 1


void countingSort(int *array, int size, int max)
{
    int *c = (int*)malloc(sizeof(int)*(max+1)); //+1, because of [0,max]
    int *b = (int*)malloc(sizeof(int)*size);

    for (int i = 0; i < max+1; i++)
    {
        c[i] = 0;
    }

    for (int  i = 0; i < size; i++)
    {
        c[array[i]] += 1;
    }

    for (int i = 1; i < max + 1; i++)
    {
        c[i] += c[i - 1];
    }

    for (int i = size - 1; i >= 0; --i)
    {
        b[c[array[i]] - 1] = array[i]; //notice -1. because of the meaning of c is the number of less or equals.
        c[array[i]] -= 1;
    }

    memcpy(array, b, size * sizeof(int));

    free(b);
    free(c);
}

int main()
{
    int array[200];
    randArray(array, 200, 100);
    coutArray(array, 200);
    countingSort(array, 200, 100);
    coutArray(array, 200);
    waitfordebug();
}

#endif // 1
