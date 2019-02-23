#include "stdafx.h"
#include "sock_lib.h"
#include "reactor_lib.h"
#include "config.h"


using namespace std;
using ::Event_Log;

Event_Log::Event_Log(const SOCK_Stream& stream, Reactor* reactor) : stream_(stream), reactor_(reactor)
{
	reactor->register_handler(this, READ_EVENT);
}

Event_Log::~Event_Log()
{
	std::cout << "Removing handler..." << std::endl;
	reactor_->remove_handler(this, READ_EVENT);
}


void Event_Log::handle_event(Handle handle, Event_Type et)
{
	if (et == READ_EVENT)
	{
		char buffer[EVENT_MAX_SIZE];
		int len = stream_.recv(buffer, EVENT_MAX_SIZE, 0);
		if (len <= 0) delete this;
		else
		{
			buffer[len] = '\0';
			auto queue = Queue::getInstance();
			queue->Enqueue(QueueMember{handle,string(buffer, 0, len)});
		}
	}
}

Handle Event_Log::get_handle() const
{
	return stream_.get_handle();
}
