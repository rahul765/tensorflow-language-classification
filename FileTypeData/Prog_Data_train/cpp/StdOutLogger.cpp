#include "StdOutLogger.h"

using namespace LogConfigTime;

void StdoutLogger::vlog(int _logLevel, const char *tag, const char *format, va_list argp)
{
	if (_logLevel >= loglevel)
	{
		vfprintf (stdout, format, argp);
	}
}
