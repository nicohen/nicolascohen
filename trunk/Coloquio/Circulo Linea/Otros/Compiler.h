#ifndef __COMPILER_H__
#define __COMPILER_H__

#include <stdio.h>
#include <string.h>
#include "Lexer.h"
#include "ParserLDM.h"
#include "ParserLDD.h"
#include "ErrorManager.h"
#include "SemLDD.h"
#include "LDMSem.h"
#include "Global.h"

typedef struct Compiler {
	Lexer* lexerLDD;
	Lexer* lexerLDM;
	ParserLDD* parserLDD;
	ParserLDM* parserLDM;
	SemLDM* semLDM;
	SemLDD* semLDD;
	ErrorManager* EM;
}Compiler;


Compiler* Compiler_Create();

void Compiler_Destroy(Compiler *compiler);

int Compiler_Compile(Compiler *compiler);

#endif