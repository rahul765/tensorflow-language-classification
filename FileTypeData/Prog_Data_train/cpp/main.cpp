//Copyright 2015 Roger Bassons
#include "Game.h"

int main() {
	Game g;
	g.initVideo();
	
	while (!g.isEnded()) {
		g.play();
		g.show();
	}
	
	return 0;
}
