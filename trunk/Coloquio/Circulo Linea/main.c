#include <stdio.h>
#include "FileManager.h"
#include "Collision.h"

void addCircle(Circle* circle) {
	return;
}

int main(int argc, char** argv) { 
	char* filePath;
	FileManager* fileManager;
	Circle* circle;
	int colisiona=FALSE;
	Point* pFrom;
	Point* pTo;

	filePath="C:\\Documents and Settings\\nicolas\\Escritorio\\Taller\\Coloquio\\Circulo Linea\\Circulos.txt";
	fileManager = createFile(filePath);

	pFrom = (Point*) malloc(sizeof(Point));
	pTo   = (Point*) malloc(sizeof(Point));

	pFrom->x=atoi(argv[1]);
	pFrom->y=atoi(argv[2]);
	pTo->x=atoi(argv[3]);
	pTo->y=atoi(argv[4]);

	while(!fileManager->EOFILE) {
		circle=getNextCircle(fileManager);
		colisiona = analize(fileManager,circle, pFrom, pTo);
		if (colisiona==TRUE)
			addCircle(circle);
	}
	
	free(pFrom);
	free(pTo);
	destroyCircle(circle);
	destroyFile(fileManager);

	printf("\n\n");

	/*
	} else { 
		printf("Cantidad Erronea de Parametros \n");
	}
	*/

	return 1;
}
