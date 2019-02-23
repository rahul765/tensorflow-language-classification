#include <iostream>
#include <string>
using namespace std;
int r[60];
#define _TOSTRING(buf, fmt, val)	\
	sprintf_s(buf, sizeof (buf), fmt, val)
inline string to_string(int _Val)
{	// convert int to string
	char _Buf[2 * _MAX_INT_DIG];

	_CSTD _TOSTRING(_Buf, "%d", _Val);
	return (string(_Buf));
}

string stringmu(string in,int xx){
	memset(r, 0, sizeof(r));
	string result;
	for (short tm = in.length()-1; tm>=0; tm--){
		r[tm]+= (in[tm] - 0x30)*xx;
		while (r[tm] > 9 && tm){
			r[tm] -= 10; r[tm - 1]++;
		}}
		for (short tm = 0; tm <in.length(); tm++){
			result += to_string(r[tm]);
			}
		//while (result[0] == '0')result = result.substr(1);
		return result;
}
int main(){
	string nn, n2;
	while (cin >> nn){
	n2 = nn + nn;
	for (short x = 1; x <= nn.length(); x++)
		if (n2.find(stringmu(nn,x)) ==-1){
			cout << nn << " is not cyclic" << endl;
			goto n;
		}
	cout << nn << " is cyclic" << endl;
n:;
	}
	return 0;
}
