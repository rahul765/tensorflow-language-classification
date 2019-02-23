#include "TransportDestinationInformation.hpp"

TransportDestinationInformation::TransportDestinationInformation(DestinationIp ip, DestinationPort port) : ip(ip), port(port)
{
}

DestinationPort TransportDestinationInformation::getPort() const
{
    return port;
}


DestinationIp TransportDestinationInformation::getIp() const
{
    return ip;
}
