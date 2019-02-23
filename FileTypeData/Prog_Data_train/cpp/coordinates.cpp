#include <iostream>
int is_valid(int input_x,int input_y);

int main(int argc, char *argv[])
{
    int num_of_inputs, input_x, input_y, result;
    std::cin >> num_of_inputs;
    for(int i=0; i< num_of_inputs; i++){
        std::cin >> input_x;
        std::cin >> input_y;
        result = is_valid(input_x, input_y);
        if(result >= 0){
            std::cout << result << std::endl;
        }
        else std::cout << "No Number" << std::endl;
    }
    return 0;
}

int is_valid(int input_x,int input_y){
    if(input_x == input_y ) {
        if(input_y%2 == 0) return((4*(input_y/2)));
        else return (1+ (4*(input_y/2)));
    }
    if(input_x == (input_y+2)){
        if(input_y%2 == 0) return(2+(4*(input_y/2)));
        else return (3+(4*(input_y/2)));
    }
    return -1;
}

