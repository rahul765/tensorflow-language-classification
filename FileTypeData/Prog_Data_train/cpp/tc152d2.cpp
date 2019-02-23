int pos
int fre
int pic
int cp = pos,dir = 1;
vector<int> nu;
cp = pos;
while( cp <= pic  ){
    nu.push_back(pic);
    cp = ( dir == 1 ) ? cp + 1 + (fre-pos) * 2 : cp + 1 + (pos - 1) * 2 ;
    dir = !dir;
}
return nu;
