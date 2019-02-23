#include <iostream>
#include <conio.h>

using namespace std;

int main()
{
	int arr[] = {1, 2, 3};
	
	
	//generate the subsets
	
	int n = 8;
	for(int i=0;i<8;i++)
	{
            cout<<"{";
            for(int j=0;j<3;j++){
            if(i & 1<<j)
            cout<<arr[j];
            
            }
            cout<<"}"<<endl;
            }
	
	cout<<getch();
	return 0;
}





//	int i, j, k =1;
//
//	for(i=0 ; i<8; i++)
//	{
//		printf("{ ");
//		j = i;
//		k = 1;
//		while(j != 0)
//		{
//			if(j & 1)
//				printf("%d ", k);
//			j = j >> 1;
//			k = k + 1;
//		}
//		printf("}\n");
//	}
