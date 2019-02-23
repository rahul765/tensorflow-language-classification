#include <iostream>
#include <cstdlib>
#include <ctime>
using namespace std;

void swap(int* a,int* b)
{
	int temp = *a;
	*a = *b;
	*b = temp;
}

void printer(int array[],int n)
{
	for(int i = 0;i < n;i++)
		cout << array[i];
	cout << endl;
}

int randomizer(int i,int n)
{
	srand(time(NULL));
	int j = rand()%(n-i);
	return j;
}

void shuffle(int array[],int n)
{
	int k;
	for(int i = 0;i < n;i++)
	{
		k = randomizer(i,n);
		swap(&array[i],&array[k]);
	}
}

int main(int argc, char* argv[])
{
//	cout << "argc =" << argc << endl;
//	for(int i = 0;i < argc; i++)
//		cout << "argv[" << i << "] = " << argv[i] << endl; 	
	int array[argc-1];
	int n = argc-1;
	for(int i = 0;i < argc-1;i++)
		array[i] = atoi(argv[i+1]);
	shuffle(array,n);
	printer(array,n); 
	return 0;
}







