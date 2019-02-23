#pragma once
#include "stdafx.h"
#include "INET_Addr.h"
#include "windows.h"

INET_Addr::INET_Addr(u_short port, u_long addr)
{
	// Set up the address to become a server.
	memset(&addr_, 0, sizeof addr_);
	addr_.sin_family = AF_INET;
	addr_.sin_port = htons(port);
	addr_.sin_addr.s_addr = inet_addr("127.0.0.1");
}


INET_Addr::~INET_Addr()
{
}

u_short INET_Addr::get_port() const
{
	return addr_.sin_port;
}

u_long INET_Addr::get_ip_addr() const
{
	return addr_.sin_addr.s_addr;
}

sockaddr* INET_Addr::addr()
{
	return reinterpret_cast <sockaddr *>(&addr_);
}


size_t INET_Addr::size() const
{
	return sizeof(addr_);
}