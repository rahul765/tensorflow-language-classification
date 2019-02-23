#pragma once
#include "stdafx.h"
#include "SOCK_Acceptor.h"
#pragma comment(lib, "ws2_32.lib")
#include "SOCK_Stream.h"
#include "INET_Addr.h"
#include <winsock.h>

SOCK_Acceptor::SOCK_Acceptor(INET_Addr& addr)
{
	// Create a local endpoint ofcommunication.
	handle_ = socket(AF_INET, SOCK_STREAM, 0);
	// Associate address with endpoint.
	bind(handle_, addr.addr(), addr.size());
	// Make endpoint listen for connections.
	listen(handle_, 5);
}

void SOCK_Acceptor::open(const INET_Addr& sock_addr)
{
	//TODO
}

void SOCK_Acceptor::accept(SOCK_Stream& s)
{
	s.set_handle(_WINSOCKAPI_::accept(handle_, 0, 0));
}

SOCKET SOCK_Acceptor::get_handle() const{
	return handle_;
}

SOCK_Acceptor::~SOCK_Acceptor()
{
}