#include <iostream>
#include <cmath>

long long  all_vals[10000000] = {0};
long long maximum_value(long long input_arg);
int main(){
    int counter = 10;

    long long input_value;
    while(counter--){
        std::cin >> input_value;
        //	input_value = 24;
        std::cout << maximum_value(input_value) << std::endl;
    }
}

long long maximum_value(long long input_arg){
    if(input_arg == 1 || input_arg == 0)
        return input_arg;
    else if(input_arg < 10000000 ){
        if (all_vals[input_arg] != 0) return  all_vals[input_arg] ;
        else all_vals[input_arg] = std::max(input_arg, maximum_value(input_arg/2) + maximum_value(input_arg/3) + maximum_value(input_arg/4));
    }
    else{
        return std::max(input_arg, maximum_value(input_arg/2) + maximum_value(input_arg/3) + maximum_value(input_arg/4));
    }
}
