#include "PacketLenghtFilter.hpp"

using namespace std;

PacketLenght PacketLenghtFilter::apply(const string line)
{
    string tmp = line.substr(79);
    const unsigned int found = tmp.find(":");

    return PacketLenght(tmp.substr(0, found));
}

