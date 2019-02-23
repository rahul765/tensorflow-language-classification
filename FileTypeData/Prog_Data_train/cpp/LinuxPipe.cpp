#include "LinuxPipe.hpp"

LinuxPipe::LinuxPipe() : pipeHandler(NULL)
{
}

void LinuxPipe::open(CaptureCommand& captureCommand)
{
    pipeHandler = popen(captureCommand.getString().c_str(), "r");
    if(NULL == pipeHandler)
    {
        throw;
    }
}

FILE* const LinuxPipe::getHandler() const
{
    return pipeHandler;
}
