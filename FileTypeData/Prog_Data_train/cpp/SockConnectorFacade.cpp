#include "stdafx.h"
#include "SockConnectorFacade.h"


SockConnectorFacade::SockConnectorFacade()
{
	handle_ = socket(AF_INET, SOCK_STREAM, 0);
}

int SockConnectorFacade::connect(sockaddr* addr, size_t len)
{
	return _WINSOCKAPI_::connect(handle_, addr, len);
	return 0;
}

SOCKET SockConnectorFacade::get_handle() const
{
	return handle_;
}

SockConnectorFacade::~SockConnectorFacade()
{
}
