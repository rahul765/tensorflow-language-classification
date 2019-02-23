#include "PacketInformation.hpp"

PacketInformation::PacketInformation(IPVersion ipVersion, PacketLenght lenght) : ipVersion(ipVersion), lenght(lenght)
{
}

PacketLenght PacketInformation::getLenght() const
{
    return lenght;
}

IPVersion PacketInformation::getIpVersion() const
{
    return ipVersion;
}
