#ifndef __FILEMANAGER_H__
#define __FILEMANAGER_H__

#include <stdio.h>
#include <string.h>
#include <stdlib.h>
#include "Global.h"

typedef struct FileManager {
	const char* filePath;
	FILE* file;
	int fileHandler;
	char currentChar;
	char nextChar;
	int EOFILE;
	int fileValid;
} FileManager;

typedef struct Circle {
	int x;
	int y;
	int r;
	char* name;
}Circle;

/* Abre el archivo y devuelve el primer registro */
FileManager* createFile(const char* filePath);

/* Devuelve el siguiente registro, si es NULL devuelve 0 */
Circle* getNextCircle(FileManager* fileManager);

/* Destruye el archivo */
void destroyFile(FileManager* fileManager);

/* Destruye el circulo */
void destroyCircle(Circle* circle);

/* Agrega la salida para el sysout */
void addCircle(Circle* circle);

#endif
