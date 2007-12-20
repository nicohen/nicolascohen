#ifndef __COLLISION_H__
#define __COLLISION_H__

#include <stdio.h>
#include <string.h>
#include <stdlib.h>
#include "FileManager.h"

typedef struct Point {
	int x;
	int y;
}Point;

typedef struct Line {
	Point* p;
	Point* q;
}Line;

/* Analiza si colisiona o no */
int analize(FileManager* fileManager, Circle* circle, Point* pFrom, Point* pTo);

#endif
