#include "stdafx.h"
#include "SockAcceptorFacade.h"
#pragma comment(lib, "ws2_32.lib")
#include "SockStreamFacade.h"
#include "INETFacade.h"
#include <winsock.h>



SockAcceptorFacade::SockAcceptorFacade(INETFacade& addr)
{
	// Create a local endpoint ofcommunication.
	handle_ = socket(AF_INET, SOCK_STREAM, 0);
	// Associate address with endpoint.
	bind(handle_, addr.addr(), addr.size());
	// Make endpoint listen for connections.
	listen(handle_, 5);
}


void SockAcceptorFacade::accept(SockStreamFacade& s)
{
	s.set_handle(_WINSOCKAPI_::accept(handle_, 0, 0));
	
}

SockAcceptorFacade::~SockAcceptorFacade()
{
}
