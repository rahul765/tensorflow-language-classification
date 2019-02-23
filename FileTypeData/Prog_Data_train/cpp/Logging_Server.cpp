#pragma once
#include "stdafx.h"
#include "Logging_Acceptor.h"
#include "INET_Addr.h"

// Logging server port number.
const u_short PORT = 10000;
const u_short ADDR = 123;
int main () {
	//// Logging server address.
	//INET_Addr addr (PORT, ADDR);
	//// Initialize logging server endpoint and register with reactor singleton.
	////Logging_Acceptor la (addr, Reactor::instance ());
	//// Event loop that processes client connection requests and log records reactively.
	//for (;;)
	//	Reactor::instance ()->handle_events ();
	////* NOTREACHED */
}