#include <iostream>
#include <bitset>
#include <math.h>
using namespace std;
bitset<150000000> mybit;
int main ()
{
	int l;float r;
	cin>>r;
	cin>>l;
	int count =0 ;
	for(int t = 3 ; t <= l ; t = t+2){

		int temp = t>>1;
		if( mybit[ temp - 1 ] == 0  ){
			int notprime = t;

			while( notprime + ( t << 1 ) <= l ){
				notprime = notprime + (t<<1) ;
				mybit[notprime/2-1] = 1;

            }
		}
	}
    float st = ceil((r-1)/4);
    //cout<<st<<endl;
	if(st < 1){st=1;}
	for(int k  = (int)st ; 4*k + 1 <= l ; k++){
		if(mybit[((4*k +1)>>1)-1] == 0    ){
			//cout<<((4*k +1)>>1)-1<<endl;
			count++;
		}
	}
	if(r==2) count++;
	cout<<count<<endl;
	return 0;
}
