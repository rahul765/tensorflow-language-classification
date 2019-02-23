#include "CaptureOutput.hpp"

using namespace std;

void CaptureOutput::operator()(CaptureCommand& captureCommand)
{
    pipe.open(captureCommand);
}

string CaptureOutput::getLine()
{
    string line = getStringFromStream(pipe);
    return line;
}
