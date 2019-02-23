#include "stdafx.h"
#include "sock_lib.h"


SOCK_Connector::SOCK_Connector()
{
	handle_ = socket(AF_INET, SOCK_STREAM, 0);
}

SOCK_Connector::~SOCK_Connector()
{
	// No resource to release
}

int SOCK_Connector::connect(sockaddr* addr, size_t len)
{
	return _WINSOCKAPI_::connect(handle_, addr, len);
}

SOCKET SOCK_Connector::get_handle() const
{
	return handle_;
}
