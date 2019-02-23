#include "FileLogger.h"

using namespace LogConfigTime;

FileLogger::FileLogger(const char *filename)
{
	F = fopen(filename,"at");
}

void FileLogger::close()
{
	fflush(F);
	fclose(F);
}

FileLogger::~FileLogger() {
	this->close();
}

void FileLogger::vlog(int _logLevel, const char *tag, const char *format, va_list argp)
{
	if (_logLevel >= loglevel)
	{
		vfprintf (F, format, argp);
	}
}
