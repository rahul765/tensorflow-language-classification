//Copyright 2015 Roger Bassons
#include "Paddle.h"

Paddle::Paddle() {
	p_.x = p_.y = p_.w = p_.h = 0;
	move_ = 0;
	speed_ = 10;
}

void Paddle::setSize(int width, int height) {
	p_.w = width;
	p_.h = height;
}

void Paddle::setPosition(int x, int y) {
	p_.x = x;
	p_.y = y;
}

int Paddle::getX() const {
	return p_.x;
}

int Paddle::getY() const {
	return p_.y;
}

int Paddle::getWidth() const {
	return p_.w;
}

int Paddle::getHeight() const {
	return p_.h;
}

void Paddle::accelerateUp() {
	move_ = 1;
}

void Paddle::accelerateDown() {
	move_ = 2;
}

void Paddle::stop() {
	move_ = 0;
}

void Paddle::move(int max) {
	if (move_ == 1) {
		int nextY = p_.y - speed_;
		if (nextY >= 0)
			p_.y = nextY;
	} else if (move_ == 2) {
		int nextY = p_.y + speed_;
		if (nextY+p_.h <= max)
			p_.y = nextY;
	}
}	

void Paddle::draw(SDL_Renderer *ren) const {
	SDL_SetRenderDrawColor(ren,255,255,255,255);
	SDL_RenderFillRect(ren,&p_);
}
	
