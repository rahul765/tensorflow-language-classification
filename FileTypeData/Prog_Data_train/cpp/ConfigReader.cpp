#include<iostream>
#include<stdio.h>
#include<string.h>
#include"ConfigReader.h"

using namespace std;
using namespace LogConfigTime;

ConfigReader *ConfigReader::instance=NULL;

const char *ConfigReader::getOverrideValueIfExists(const char *sectionname, const char *keyname, const int argc, const char **argv)
{
	char buffer[255];
	if (argc<2)
	{
		return NULL;
	}

	if (sectionname)
	{
		sprintf(buffer,"--%s/%s=",sectionname,keyname);
	}
	else
	{
		sprintf(buffer,"--%s=",keyname);
	}

	for(int i=1; i<argc; i++)
	{
		if (strstr(argv[i],buffer)==argv[i])
		{
			const char *ptr = argv[i]+strlen(buffer);
			cout << "Config override for "<<buffer<<" = "<<ptr<<endl;
			return ptr;
		}
	}
	return NULL;
}

