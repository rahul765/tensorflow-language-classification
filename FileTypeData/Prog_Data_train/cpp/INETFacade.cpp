#include "stdafx.h"
#include "INETFacade.h"


INETFacade::INETFacade(u_short port, char* addr)
{
	// Set up the address to become a server.
	memset(&addr_, 0, sizeof addr_);
	addr_.sin_family = AF_INET;
	addr_.sin_port = htons(port);
	addr_.sin_addr.s_addr = inet_addr(addr);
}


INETFacade::~INETFacade()
{
}

u_short INETFacade::get_port() const
{
	return addr_.sin_port;
}

u_long INETFacade::get_ip_addr() const
{
	return addr_.sin_addr.s_addr;
}

sockaddr* INETFacade::addr()
{
	return reinterpret_cast <sockaddr *>(&addr_);
}


size_t INETFacade::size() const
{
	return sizeof(addr_);
}