#include <iostream>

int main(int argc, char *argv[])
{
    int input[3] ;
    for(int i=0; i<3; i++)
        std::cin >> input[i];

    while(!(input[0] == 0 && input[1] == 0 && input[2] == 0)){
        if((input[2]) - (input[1]) == (input[1]) - (input[0])) std::cout << "AP " << input[2] + (input[1]) - (input[0]);
        else std::cout << "GP " << input[2] * (input[1]) /(input[0]);
        std::cout << std::endl;
        for(int i=0; i<3; i++)
            std::cin >> input[i];
    }
}


