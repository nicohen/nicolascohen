#include "Compiler.h"

Compiler* Compiler_Create(char* pathLDDFile) { 
	Compiler* compiler;
	char* pathLDMFile;

	pathLDMFile=NULL;

	compiler = MALLOC (Compiler,1) ;
	compiler->EM = EM_Create();
	
	compiler->lexerLDD = createLexer(pathLDDFile,compiler->EM,MODULE_LDD);

	if (compiler->lexerLDD->fileValid==TRUE) { 
		printf("SISTEMA ANALIZADOR - LEXICO SINTACTICO SEMANTICO\n");
		printf("================================================\n\n");
		compiler->semLDD = semLDD_create(compiler->semLDM,compiler->EM);	
		compiler->parserLDD=ParserLDD_Create(compiler->EM,compiler->lexerLDD,compiler->semLDD);	
		pathLDMFile = ParseLDD_GetUsing(compiler->parserLDD);
		compiler->lexerLDM = createLexer(pathLDMFile,compiler->EM,MODULE_LDM); 
		
		FREE(pathLDMFile);
		if (compiler->lexerLDM->fileValid==TRUE) { 
			compiler->semLDM = semLDM_createLDMSem(compiler->EM);
			compiler->parserLDM = ParserLDM_Create(compiler->EM,compiler->lexerLDM,compiler->semLDM);
			compiler->parserLDD->sem->LDM=compiler->semLDM;
		}
	}

	return compiler;
}

int Compiler_Compile(Compiler* compiler){
	if (compiler->lexerLDD->fileValid==TRUE && compiler->lexerLDM->fileValid==TRUE) { 
		ParserLDM_Analize(compiler->parserLDM);
		if (EM_CountErrors(compiler->EM,MODULE_LDM,0)==0) 
			ParserLDD_Analize(compiler->parserLDD);
		else 
			printf("No se analiza LDD a causa de errores en LDM");

	}
	return 1;
}

void Compiler_Destroy(Compiler* compiler) {
	if (compiler->lexerLDD->fileValid==TRUE) {
		
		semLDD_destroy(compiler->semLDD);
		ParserLDD_Destroy(compiler->parserLDD);
		
		if (compiler->lexerLDM->fileValid==TRUE) { 	
			semLDM_destroyLDSem(compiler->semLDM);
			ParserLDM_Destroy(compiler->parserLDM);
		}

		destroyLexer(compiler->lexerLDM);
	}
	destroyLexer(compiler->lexerLDD);
	EM_Destroy(compiler->EM);

	FREE(compiler);
}
