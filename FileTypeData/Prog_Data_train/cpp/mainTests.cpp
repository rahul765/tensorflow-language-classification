
#include "CaptureOutput.hpp"
#include "CaptureCommand.hpp"
#include "ProcessOutput.hpp"
#include "Tcpdump.hpp"
#include "FileStorage.hpp"

#include <gtest/gtest.h>

using namespace std;

static const string FILE_TEST = "test1.txt";
static const string TCPDUMP_ARGUMENTS = "-i eth1 -n -e -tttt -q -l not arp";

/*TEST(mainTests, mainWithTcpDump)
{
    Tcpdump captureCommand(TCPDUMP_ARGUMENTS);

    CaptureOutput captureOutput;
    captureOutput(captureCommand);

    ProcessOutput processOutput;
    FileStorage storage(FILE_TEST);
    processOutput(captureOutput, storage);

    EXPECT_EQ(1, 0);
}*/
