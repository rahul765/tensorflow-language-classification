#include <sstream>
#include <stdio.h>
#include "PingMessage.h"
#include "Logger.h"

PingMessage::PingMessage(char *json)
{
	typecode = Ping;
	parse(json);
}

PingMessage::PingMessage()
{
	typecode = Ping;
}

bool PingMessage::parse(char *json)
{
	return true;
}

void PingMessage::writeJson(char *buffer)
{
	sprintf(buffer,"{ \"type\": \"ping\", \"desiredtimestamp\": \"0\" }#");
}


void PingMessage::log()
{
	LogConfigTime::Logger::getInstance()->Log(LogConfigTime::Logger::LOGLEVEL_INFO,"Message","PingMessage()\n");
}
