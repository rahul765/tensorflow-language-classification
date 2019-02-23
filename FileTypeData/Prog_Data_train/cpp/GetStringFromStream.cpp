#include "GetStringFromStream.hpp"

#include <cstdio>

using namespace std;

static const unsigned int CAPTURE_LENGHT = 500;

string GetStringFromStream::operator()(LinuxPipe& pipe) const
{
    char lineArr[CAPTURE_LENGHT];
    fgets(lineArr, CAPTURE_LENGHT, pipe.getHandler());
    string line = lineArr;
    return line;
}
