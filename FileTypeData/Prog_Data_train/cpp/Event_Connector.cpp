#include "stdafx.h"

#include "reactor_lib.h"
#include "sock_lib.h"

using ::Event_Connector;

Event_Connector::Event_Connector(Reactor* reactor) : reactor_(reactor)
{
}

Event_Connector::~Event_Connector()
{
}

void Event_Connector::handle_event(Handle handle, Event_Type et)
{
	sh_(handle);
}

Handle Event_Connector::get_handle() const
{
	return connector_.get_handle();
}

void Event_Connector::connect(ServiceHandle sh, INET_Addr addr)
{
	sh_ = sh;
	SOCK_Stream* peer_stream = new SOCK_Stream();
	peer_stream->set_handle(connector_.get_handle());
	connector_.connect(addr.addr(), addr.size());
	sh_(peer_stream->get_handle());
}
