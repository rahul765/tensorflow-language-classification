#include <android/log.h>
#include "AndroidLogger.h"

namespace Logging
{

	void AndroidLogger::vlog(int _logLevel, const char *tag, const char *format, va_list argp)
	{
		// filtering not needed here, Android LogCat has built-in filter
		int prio = 0;
		switch(_logLevel) {
			case Logger::LOGLEVEL_ERROR: prio = ANDROID_LOG_ERROR; break;
			case Logger::LOGLEVEL_WARNING: prio = ANDROID_LOG_WARN; break;
			case Logger::LOGLEVEL_INFO: prio = ANDROID_LOG_INFO; break;
			case Logger::LOGLEVEL_DEBUG: prio = ANDROID_LOG_DEBUG; break;
			case Logger::LOGLEVEL_VERBOSE: prio = ANDROID_LOG_VERBOSE; break;
			default: break;
		}

		__android_log_vprint(prio, tag, format, argp);
	}

}
