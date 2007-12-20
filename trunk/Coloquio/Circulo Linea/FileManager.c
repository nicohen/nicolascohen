#include "FileManager.h"

FileManager* createFile(const char* filePath) {
	FileManager* fileManager;
	fileManager=(FileManager*)malloc(sizeof(FileManager));
	fileManager->currentChar=EOSTR;
	fileManager->EOFILE=0;
	fileManager->nextChar=EOSTR;
	fileManager->file=fopen(filePath, "r");
	fileManager->filePath=filePath;
	fileManager->fileValid=TRUE;
	if(fileManager->file==NULL){
		fileManager->EOFILE=TRUE;
		fileManager->fileValid=FALSE;
	}else{
		fileManager->nextChar=getc(fileManager->file);
		if(fileManager->nextChar==EOF){
			fileManager->EOFILE=TRUE;
			fileManager->nextChar=EOSTR;
		}
	}	
	return fileManager;
}

void destroyFile(FileManager* fileManager) {
	free(fileManager);
}

Circle* createCircle() {
	Circle* circle;
	circle = (Circle*) malloc(sizeof(Circle));
	circle->name = (char*) malloc(sizeof(char));
	return circle;
}

void destroyCircle(Circle* circle) {
	free(circle->name);
	free(circle);
}

void readNext(FileManager* fileManager) {
	fileManager->currentChar=fileManager->nextChar;
	
	if(!fileManager->EOFILE){
		fileManager->nextChar=getc(fileManager->file);
		if (fileManager->nextChar==EOF){
			fileManager->nextChar=EOSTR;
			fileManager->EOFILE=TRUE;
		}
	}else{
		fileManager->nextChar=EOSTR;
	}
}


Circle* getNextCircle(FileManager* fileManager) {
	char* cadena;
	Circle* circle = createCircle();
	char espacio = ' ';
	int xDone;
	int yDone; 
	int rDone;
	int sDone;
    int exit = FALSE;

	cadena = (char*) malloc(sizeof(char));

	strcpy(cadena,"\0");

	xDone=FALSE;
	yDone=FALSE;
	rDone=FALSE;
	sDone=FALSE;

	do {
		readNext(fileManager);
		if (fileManager->currentChar!=espacio && fileManager->currentChar!='\n' && !fileManager->EOFILE) {
			strncat(cadena,&fileManager->currentChar,1);
		} else {
			if (xDone==FALSE) {
				circle->x = atoi(cadena);
				xDone=TRUE;
				strcpy(cadena,"\0");
			} else {
				if (yDone==FALSE) {
					circle->y = atoi(cadena);
					yDone=TRUE;
					strcpy(cadena,"\0");
				} else {
					if (rDone==FALSE) {
						circle->r = atoi(cadena);
						rDone=TRUE;
						strcpy(cadena,"\0");
					} else {
						if (sDone==FALSE) {
							strcpy(circle->name,cadena);
							strcat(circle->name,"\0");
							sDone=TRUE;
							strcpy(cadena,"\0");
						}
					}
				}
			}
		}
	} while(fileManager->currentChar!='\n' && !fileManager->EOFILE);

	return circle;

}
