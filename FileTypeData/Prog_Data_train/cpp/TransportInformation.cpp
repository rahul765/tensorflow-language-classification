#include "TransportInformation.hpp"

TransportInformation::TransportInformation(TransportDestinationInformation destination, TransportSourceInformation source) : destination(destination), source(source)
{

}

TransportDestinationInformation TransportInformation::getDestination() const
{
    return destination;
}

TransportSourceInformation TransportInformation::getSource() const
{
    return source;
}
