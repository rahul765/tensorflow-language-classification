
#include "Tcpdump.hpp"

#include <gtest/gtest.h>

using namespace std;

static const string TCPDUMP_ARGUMENTS = "-v -ttt";
static const string TCPDUMP = "tcpdump";
static const string TCPDUMP_COMMAND = TCPDUMP + " " + TCPDUMP_ARGUMENTS;

TEST(TcpdumpTests, tcpdumpWithArguments)
{
    Tcpdump tcpdump(TCPDUMP_ARGUMENTS);

    EXPECT_EQ(TCPDUMP_COMMAND, tcpdump.getString());
}
