#include <sstream>
#include <stdio.h>
#include "SendlogMessage.h"
#include "Logger.h"

SendlogMessage::SendlogMessage(char *json)
{
	typecode = Sendlog;
	parse(json);
}

SendlogMessage::SendlogMessage()
{
	typecode = Sendlog;
}

bool SendlogMessage::parse(char *json)
{
	return true;
}

void SendlogMessage::writeJson(char *buffer)
{
	sprintf(buffer,"{ \"type\": \"sendlog\", \"desiredtimestamp\": \"0\" }#");
}


void SendlogMessage::log()
{
	LogConfigTime::Logger::getInstance()->Log(LogConfigTime::Logger::LOGLEVEL_INFO,"Message","SendlogMessage()\n");
}
