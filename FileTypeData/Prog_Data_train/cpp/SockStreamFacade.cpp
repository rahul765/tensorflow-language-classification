#include "stdafx.h"
#include "SockStreamFacade.h"
#include <winsock.h>
#pragma comment(lib, "ws2_32.lib")

typedef int ssize_t;
SockStreamFacade::SockStreamFacade() : handle_(-1)
{
}

SockStreamFacade::SockStreamFacade(::SOCKET h) : handle_(h)
{
	handle_ = h;
}

SockStreamFacade::~SockStreamFacade()
{
	closesocket(handle_);
}

void SockStreamFacade::set_handle(::SOCKET h)
{
	handle_ = h;
}

SOCKET SockStreamFacade::get_handle() const
{
	return handle_;
}

ssize_t SockStreamFacade::recv(char* buf, size_t len, int flags)
{
	return _WINSOCKAPI_::recv(handle_,buf, len, flags);
}

ssize_t SockStreamFacade::send(const char* buf, size_t len, int flags)
{
	return _WINSOCKAPI_::send(handle_,buf, len, flags);
}
