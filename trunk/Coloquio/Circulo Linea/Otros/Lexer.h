#ifndef __LEXER_H__
#define __LEXER_H__

#include "Global.h"
#include "ErrorManager.h"
#include "stdio.h"

#define TEXT_SIZE 500
#define POINTER_SIZE 100
#define EOSTR '\0'
#define SCAPE_CHARACTER '\\'

#define TYPE_UNKNOWN  0
#define TYPE_IDENTIFIER 1
#define TYPE_KEYWORD 2
#define TYPE_CONSTANT 3
#define TYPE_OPERATOR 4
#define TYPE_SEPARATOR 5
#define TYPE_COMMENT 6
#define TYPE_EOF 7

typedef struct Token{
	int type;// permitidos: TYPE_UNKNOWN ,TYPE_IDENTIFIER, TYPE_KEYWORD,TYPE_CONSTANT, TYPE_OPERATOR , TYPE_SEPARATOR, TYPE_COMMENT , TYPE_EOF 
	char* value;
	int lenght;
	int column;
	int tokenWithError;
	int row;
	int literalType;//LITERALTYPE_UNKNOWN,LITERALTYPE_INTEGER,LITERALTYPE_REAL,LITERALTYPE_STRING
	int bufferLenght;
	int hasError;//TRUE/FALSE
}Token;


typedef struct Lexer{
	int column;
	int row;
	int module;
	int EOFILE;
	char currentChar;
	char nextChar;
	FILE *file;
	char *filePath;
	ErrorManager *EM;
	int fileValid;
}Lexer;

/* Crea una instancia de la estructura Lexer*/
Lexer* createLexer(const char* filePath,ErrorManager *EM, int module);

/* Destruye la instancia de la estructura Lexer*/
void destroyLexer(Lexer *ls);

/* Crea una instancia de la estructura Token*/
Token* createToken();

/* Destruye la instancia de la estructura Token*/
void destroyToken(Token* token);

/* Devuelve el siguiente token omitiendo los que no sean utiles*/
Token *getNextToken(Lexer *ls);

/* Devuelve el siguiente token omitiendo o no los que no sean utiles*/
Token *getNextToken_TrashOptional(Lexer *ls,int omitTrash);

#endif