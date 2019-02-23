#include "CommonInclude.h"

#if 0


int partion(int *array, int left, int right)
{
    if (left < right)
    {
        int flag = array[right - 1];
        int i = left;
        for (int j = left; j < right - 1; j++)
        {
            if (array[j] < flag)
            {
                swap(array + i, array + j);
                ++i;
            }
        }
        swap(array + i, array + right - 1);
        return i;
    }
    return left;
}

void quikSort(int *array, int left, int right)
{
    if (left < right)
    {
        int q = partion(array, left, right);
        quikSort(array, left, q);
        quikSort(array, q + 1, right);
    }
}


int main()
{
    int array[100];
    randArray(array, 100, 999);

    quikSort(array, 0, 100);

    coutArray(array, 100, 5);
    waitfordebug();
}


#endif // 1
