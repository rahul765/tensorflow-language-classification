#include <iostream>
#include <cmath>
#include <cstdlib>

int main()
{
    int num_of_inputs;
    std::cin >> num_of_inputs;

    while(num_of_inputs-- > 0){
        int max_idx,mul_val,carry,input;
        std::cin >> input;
        int arr_size = ceil(log2(input ) + 1);
        int* arr  = (int *)malloc(arr_size);

        std::cout << arr << std::endl;
        arr[0]=1;
        max_idx=1;

        carry = 0;
        for(int i=input; i>98; i--){
            for(int j=0;j<max_idx;j++){
                mul_val = arr[j]*i+carry;
                arr[j]=mul_val%10;
                carry = mul_val/10;
            }
            while(carry>0){
                arr[max_idx] = carry%10;
                carry /= 10;
                max_idx++;
            }
        }
        std::cout << arr << std::endl;

        for(int i=max_idx-1;i>=0;i--) std::cout << arr[i];
        std::cout << std::endl;
    }
    return 0;
}

