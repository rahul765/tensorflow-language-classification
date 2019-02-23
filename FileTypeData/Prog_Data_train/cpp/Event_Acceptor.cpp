#include "stdafx.h"
#include "sock_lib.h"
#include "reactor_lib.h"

using ::Event_Acceptor;


Event_Acceptor::Event_Acceptor(INET_Addr& addr, Reactor* reactor) : acceptor_(addr), reactor_(reactor)
{
	reactor_->register_handler(this, ACCEPT_EVENT);
}

Event_Acceptor::~Event_Acceptor()
{
	// No resources to release
}

void Event_Acceptor::handle_event(Handle handle, Event_Type et)
{
	if (et == ACCEPT_EVENT)
	{
		Event_Acceptor::accept(handle, et);
	}
}

Handle Event_Acceptor::get_handle() const
{
	return acceptor_.get_handle();
}

void Event_Acceptor::accept(Handle handle, Event_Type et)
{
	printf("Event_Acceptor-> Accepting a new client...\n");
	SOCK_Stream* new_client = new SOCK_Stream();
	acceptor_.accept(*new_client);
	Event_Log* handler = new Event_Log(*new_client, reactor_);
}
