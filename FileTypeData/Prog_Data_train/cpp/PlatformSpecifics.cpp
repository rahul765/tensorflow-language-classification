#include "PlatformSpecifics.h"
#ifdef _WIN32
#include "PlatformSpecificsWin32.h"
#endif

PlatformSpecifics *PlatformSpecifics::instance;

PlatformSpecifics *PlatformSpecifics::getInstance()
{
	if (!instance)
	{
#ifdef _WIN32
		instance = (PlatformSpecifics*)new PlatformSpecificsWin32();
#else
#error	TODO: PlatformSpecifics implementation is not available!
#endif
	}
	return instance;
}

