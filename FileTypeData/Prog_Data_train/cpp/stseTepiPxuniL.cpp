
#include "CaptureCommandFake.hpp"

#include "LinuxPipe.hpp"
#include "CaptureCommand.hpp"

#include <gtest/gtest.h>

using namespace std;

TEST(LinuxPipeTests, noErrorsOpeningThePipe)
{
    CaptureCommandFake command;
    LinuxPipe pipe;

    EXPECT_NO_THROW(pipe.open(command));
}
