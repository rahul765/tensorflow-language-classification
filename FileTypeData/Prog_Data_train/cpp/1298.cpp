#include <iostream>
int main(){
	char input[200]; int i;
	while (1){
		gets(input);
		if (!strcmp(input, "START") || !strcmp(input, "END"))continue;
		if (!strcmp(input, "ENDOFINPUT"))return 0;
		for (i = 0; input[i] != '\0'; i++)
		{
			if (input[i] >= 'A'&&input[i] <= 'Z')
			{
				input[i] -= 5; if (input[i] < 'A')input[i] += 26;
			}
			printf("%c", input[i]);
		}
		printf("\n");
	}
}
