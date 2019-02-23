#include "Tcpdump.hpp"

using namespace std;

Tcpdump::Tcpdump(string arguments):arguments(arguments),
                                command("tcpdump")
{
}

string Tcpdump::getString()
{
    return command + " " + arguments;
}
