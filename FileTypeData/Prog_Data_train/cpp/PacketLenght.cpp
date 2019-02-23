#include "PacketLenght.hpp"

using namespace std;

PacketLenght::PacketLenght(const std::string packetLenght) : packetLenght(packetLenght)
{
}

const char* const PacketLenght::getPrintFormat() const
{
    return packetLenght.c_str();
}

bool PacketLenght::operator==(const PacketLenght& pl) const
{
    return pl.packetLenght == packetLenght;
}
