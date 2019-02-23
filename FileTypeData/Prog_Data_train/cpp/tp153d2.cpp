sales
daysAvailable

int months = 0,double = 0;
for(int t = 0 ; t < sales.size() ; t++){

    if(daysAvailable[t] != 0  ){
                            months++;
                            sum = sum + ceil(sales[t]/dayAvailable[t])*30;
    }
}
    sum =ceil(sum /month);
    return sum;

