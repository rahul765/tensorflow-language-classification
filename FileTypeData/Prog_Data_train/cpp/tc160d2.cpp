int ly=-100000;,hy=100000,lx=-100000,hx=100000;
for(int t = 0 ; t*2 - 2< v.size() ; t++){
    sort(x.begin()+t*2,x.begin()+2+t*2);
    sort(y.begin()+t*2,y.begin()+2+t*2);
}

for(int t = 0 ; t*2 - 2< v.size() ; t++){
    lx= max(lx,x[t*2]);
    ly= max(ly,y[t*2]);
    hx= min(hx,x[t*2+1]);
    hy= min(hy,y[t*2+1]);
}
return (hx-lx)*(hy-ly);
