/* Program to convert a ASCII number to integer without atoi */

#include <stdio.h>
#include <stdlib.h>

void ascii_to_int(char* str) {
  int result=0;
  int sign = 1;  //Track if number is positive or negative
  int i = 0;
  if ( str[0] == '-') {
    sign = -1; //negative number
	  i++;
  }

  for(;str[i] != '\0'; i++) {
    if (str[i] < '0' || str[i] > '9') {
	  printf("\n Invalid number.\n");
	  exit(1);
	}
	result = (result*10) + str[i] - '0';
  }
  result*=sign;
  printf("\n The integer is %d \n", result);
}

int main() {
	char input[] = "-1234";
	ascii_to_int(input);
}