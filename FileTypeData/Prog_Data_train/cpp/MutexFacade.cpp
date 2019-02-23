#include "stdafx.h"
#include "MutexFacade.h"




MutexFacade::MutexFacade()
{
	InitializeCriticalSection(&mutex_);
}


MutexFacade::~MutexFacade()
{
	DeleteCriticalSection(&mutex_);
}

void MutexFacade::aquire()
{
	EnterCriticalSection(&mutex_);
}

void MutexFacade::release()
{
	LeaveCriticalSection(&mutex_);
}


