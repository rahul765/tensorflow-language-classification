#include "TransportSourceInformation.hpp"

TransportSourceInformation::TransportSourceInformation(SourceIp ip, SourcePort port) : ip(ip), port(port)
{

}

SourcePort TransportSourceInformation::getPort() const
{
    return port;
}

SourceIp TransportSourceInformation::getIp() const
{
    return ip;
}
