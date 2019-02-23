#include  <iostream>
#include <vector>
void parseOneTermAndPrint();

std::string original;
int cursor = 0;

int main(){

    int num_of_inputs = 0;
    std::cin >> num_of_inputs;
    for(int i=0; i<num_of_inputs; i++){
        cursor = 0;
        std::cin >>     original;
        parseOneTermAndPrint();
       std::cout <<  std::endl;
    }
}

    void parseOneTermAndPrint() {
        bool expression = original[cursor] == '(';
        if (expression) {
            cursor++; // open bracket
            parseOneTermAndPrint();
            char operatorval = original[cursor++];
            parseOneTermAndPrint();
            std::cout << operatorval ;
            cursor++; // close bracket
        } else { // single variable
            std::cout << original[cursor];
            cursor++;
        }
    }
