#include <iostream>

int main(int argc, char *argv[])
{
    int num_of_packets;
    std::cin >> num_of_packets;
    int *packet_container = new int(num_of_packets);
    while(num_of_packets != -1){
        int total_count = 0;
        for(int i=0; i<num_of_packets; i++){
            std::cin >> packet_container[i] ;
            total_count += packet_container[i];
        }
        int average = total_count/num_of_packets;
        if(total_count%num_of_packets != 0){
            std::cout << -1 << std::endl;
        }
        else{
            int total_shift = 0;
            for(int i=0; i<num_of_packets; i++){
                int diff = packet_container[i] - average;
                if(diff > 0){
                    total_shift += diff;
                }
            }
            std::cout << total_shift << std::endl;
        }
        std::cin >> num_of_packets;
    }
}

