#include <iostream>

int main(int argc, char *argv[])
{
    int input,factor1,factor2,idx;
    std::string encrypted;
    std::cin >> input;

    while(input != 0){
        std::cin >> encrypted;
        for(int i=0 ;i< input; i++){
            factor1 = (2*input) - (1+2*i);
            factor2 = (2*input) - factor1;
            idx = i;
            for(int j=0; j<(encrypted.size()/input); j++){
                if(j%2 == 0){
                    std::cout << encrypted[idx]  ;
                    idx += factor1;
                }
                else{
                    std::cout << encrypted[idx]  ;
                    idx +=  factor2;
                }
            }
        }
        std::cout << std::endl;
        std::cin >> input;
    }
    std::cout << std::endl;
}
