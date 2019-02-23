
#include "FileStorage.hpp"

#include <iostream>

using namespace std;

FileStorage::FileStorage(string fileName) : fileName(fileName)
{
}

void FileStorage::open()
{
    file.open(fileName.c_str());
}

void FileStorage::close()
{
    file.close();
}

void FileStorage::write(const Register& reg)
{
    file<<reg.getTimeStamp().getDate().getPrintFormat()<<" "
        <<reg.getTimeStamp().getTime().getPrintFormat()<<" "
        <<reg.getPacketInformation().getIpVersion().getPrintFormat()<<" "
        <<reg.getPacketInformation().getLenght().getPrintFormat()<<" "
        <<reg.getTransportInformation().getSource().getIp().getPrintFormat()<<" "
        <<reg.getTransportInformation().getSource().getPort().getPrintFormat()<<" "
        <<reg.getTransportInformation().getDestination().getIp().getPrintFormat()<<" "
        <<reg.getTransportInformation().getDestination().getPort().getPrintFormat()<<"\n";
}

