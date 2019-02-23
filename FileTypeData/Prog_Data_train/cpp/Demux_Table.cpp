#include "stdafx.h"
#include "reactor_lib.h"

using ::Demux_Table;
using ::Handle;
using std::map;
using ::Event_Tuple;

void Demux_Table::convert_to_fd_sets(fd_set& read_fds, fd_set& write_fds, fd_set& except_fds)
{
	FD_ZERO(&read_fds);
	FD_ZERO(&write_fds);
	FD_ZERO(&except_fds);

	map<Handle, Event_Tuple>::iterator it;

	for (it = table_.begin(); it != table_.end(); ++it)
	{
		Event_Type et = it->second.event_type_;
		if (it->second.event_handler_ == nullptr) continue;
		if (et == ACCEPT_EVENT || et == READ_EVENT)
		{
			FD_SET(it->second.event_handler_->get_handle(), &read_fds);
		}
		else if (et == WRITE_EVENT)
		{
			FD_SET(it->second.event_handler_->get_handle(), &write_fds);
		}
		else
		{
			// TODO Handle rest of cases
		}
	}
}
