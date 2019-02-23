#include "VideoInputFactory.h"

#include "VideoInputGeneric.h"
#ifdef _WIN32
#include "VideoInputPs3Eye.h"
#endif

//TODO videoInputType sould be an enumeration
VideoInput *VideoInputFactory::CreateVideoInput(int videoInputType)
{
	switch (videoInputType)
	{
	case VIDEOINPUTTYPE_GENERIC:
		return new VideoInputGeneric();
	break;
#ifdef _WIN32
	case VIDEOINPUTTYPE_PS3EYE:
		return new VideoInputPs3Eye();
	break;
#endif
	}
	return NULL;
}
