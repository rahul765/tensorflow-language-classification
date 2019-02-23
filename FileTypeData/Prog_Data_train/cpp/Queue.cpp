#include "stdafx.h"
#include "Queue.h"

Queue* Queue::instance = NULL;

Queue* Queue::getInstance()
{
	if (!instance)
	{
		instance = new Queue;
	}
	return instance;
}

Queue::~Queue()
{
}

bool Queue::Enqueue(QueueMember member)
{
	if (maxItems_ == queue_.size())
		return false;
	else
	{
		queue_.push(member);
		return true;
	}
}

QueueMember Queue::Dequeue()
{
	QueueMember x = queue_.front();
	queue_.pop();
	return x;
}

bool Queue::CheckDequeuePossible()
{
	return queue_.size() > 0;
}

int Queue::GetQueueSize()
{
	return queue_.size();
}
