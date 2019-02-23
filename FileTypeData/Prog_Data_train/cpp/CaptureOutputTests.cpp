
#include "CaptureCommandMock.hpp"

#include "CaptureCommand.hpp"
#include "CaptureOutput.hpp"
#include "Tcpdump.hpp"

#include <gtest/gtest.h>

using namespace std;

TEST(CaptureOutputTests, shouldCallExecuteCaptureCommandOneTime)
{
    CaptureCommandMock captureCommand;

    EXPECT_CALL(captureCommand, getString()).Times(1);

    CaptureOutput captureOutput;
    captureOutput(captureCommand);
}

