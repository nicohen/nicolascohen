#include "Lexer.h"
#include "myAlloc.h"

/* Destruye la instancia de la estructura Lexer*/
void destroyLexer(Lexer *ls){
	if(ls->file!=NULL){
		fclose(ls->file);
	}
	FREE(ls);
	return;
}



int isCharacter(char character){
	return (character>=0 && character<=255);
}

int isLetter(char character){
	return (character>=97 && character<=122) || (character>=65 && character<=90);
}

int isNumber(char character){
	return (character>=48 && character<=57);
}

int isNumeric(char character){
	return (isNumber(character) || character=='+' || character=='-' || character==',');
}

int isComment(char character,char nextCharacter){
	return (character=='/' && nextCharacter=='/');
}

int isOperator(char character){
	return (character=='}' || 
		character=='{' || 
		character=='(' || 
		character==')' || 
		character==':' || 
		character==',' || 
		character==';' || 
		character=='<' || 
		character=='>' || 
		character=='='
		);
}

int isSeparator(char character){
	return (character==' ' || 
		character=='\n' || 
		character=='\r' || 
		character=='\t' ||
		isOperator(character)
		);
}

int isStringConstant(Lexer *ls, Token *token){
	int scapeCharacterCount,i;
	if(*token->value!='"'){
		return FALSE;
	}

	if(*(token->value+token->lenght-1)!='"' || token->lenght==1){
		EM_RegisterError(ls->EM,ls->module,EM_LEXICAL,"cadena no cerrada.",ls->row,ls->column);
	}else{
		scapeCharacterCount=0;
		for(i=token->lenght-2;*(token->value+i)=='\\';i--){//corto cuando el caracter deja de ser de escape							
			scapeCharacterCount++;
		}
		/*Si la cantidad de caracteres de escape consecutivos es Par  ->Final de la cadena
		  Si la cantidad de caracteres de escape consecutivos es Impar->Comilla escapada*/
		if(scapeCharacterCount % 2 ==0){//Si es par, es fin de cadena
			//Todo bien
		}else{
			//Se escapo la ultima comilla, error
			EM_RegisterError(ls->EM,ls->module,EM_LEXICAL,"cadena no cerrada porque se escapo la comilla.",ls->row,ls->column);
		}
	}
	token->literalType=LITERALTYPE_STRING;
	return TRUE;
}

int isSupportedFor_Identifiers(char *character){
	return (isLetter(*character) || isNumber(*character) || *character=='_' );
}

int isIdentifier(Lexer *ls,Token *token){
	int result=FALSE;
	int i;
	char *currentChar;
	int cicleCounter=0;
	// Si no hay una letra o un guion bajo al principio, no es un identificador
	result = (*(token->value)=='_' || isLetter(*token->value));
	if(result){
		for(i=0;i<token->lenght && !token->hasError;i++){
			currentChar=token->value+i;
			if(i>=0){
				if(!isSupportedFor_Identifiers(token->value+i)){
					EM_RegisterError(ls->EM,ls->module,EM_LEXICAL,"caracter invalido como identificador.",token->row,token->column+i);
					token->hasError=TRUE;
				}
			}			
		}
		if(!token->hasError){
		
		}
	}
	return result;
}

int isCommentToken(char *character,int lenght){
	int i;
	if(lenght<2){
		return FALSE;
	}
	for(i=0;i<lenght;i++){
		if( (*character!='/' && (i==0 || i==1))){
			return FALSE;
		}else{
			
		}
	}
	return TRUE;
}

#define KeyCount 10

int isKeyword(char *character,int lenght){
	int i,j,exit;
	char keywords[KeyCount][9]={"real\0","string\0","int\0","list\0","array\0","type\0","required\0","default\0","using\0","object\0"};
	for(i=0;i<KeyCount;i++){
		exit=FALSE;
		for(j=0;j<=lenght && !exit;j++){
			if(keywords[i][j]==EOSTR && j==lenght){
				return TRUE;
			}
			if(keywords[i][j]!=(*(character+j)) || (keywords[i][j]==EOSTR && j<lenght)){
				exit=TRUE;
			}
		}
	}
	return FALSE;
}

int isIntInRange(const char* s, unsigned int max) {
	unsigned int cifras = 0;
	unsigned int iMaximo = max;
	char* sMaximo;
	unsigned int i;
	int res = TRUE;

	while(iMaximo>0) {
		iMaximo = iMaximo/10;
		cifras++;
	}

	sMaximo = (char*) malloc(max(cifras, strlen(s))+1);
	memset(sMaximo, 0, max(cifras, strlen(s))+1);

	//if(SHOW_INTERNAL_MESSAGES){
		sprintf(sMaximo, "%u", max);
	//}

	if( cifras < strlen(s)) {
		memcpy(sMaximo+(strlen(s)-cifras), sMaximo, cifras);
		memset(sMaximo, '0', strlen(s)-cifras);
		cifras = strlen(s);
	}

	if( cifras == strlen(s)) {
		for(i=0;i<cifras;i++) {
			if (sMaximo[i]<s[i]) {
				res = FALSE;
				break;
			} else if(sMaximo[i]>s[i]) {
				break;
			}
		}
	}

	free (sMaximo);

	return res;
}

int isNumericLiteral(Lexer *ls,Token *token){
	int result=FALSE;
	int i;
	char *currentChar;
	char plusOrNegSign=0;
	char decimalSeparatorFound=0;
	char numberFound=0;
	int cicleCounter=0;
	char *number;
	result = (*token->value=='+' || *token->value=='-' || *token->value=='.' || isNumber(*token->value));
	if(result){
		for(i=0;i<token->lenght && !token->hasError;i++){
			currentChar=token->value+i;
			
			//El signo no puede estar en otra posicion que no sea al principio del numero
			if(*currentChar=='+' || *currentChar=='-'){
				if(plusOrNegSign){
					//Formato de numero incorrecto (mas de un signo)
					EM_RegisterError(ls->EM,ls->module,EM_LEXICAL,"formato de numero incorrecto, se encontro mas de un signo.",token->row,token->column);
					token->hasError=TRUE;
				}
				plusOrNegSign=TRUE;
			}
			if(*currentChar=='.'){
				if(decimalSeparatorFound){
					//Formato de numero incorrecto (mas de un separador decimal)
					EM_RegisterError(ls->EM,ls->module,EM_LEXICAL,"formato de numero incorrecto, se encontro mas de separador decimal.",token->row,token->column);
					token->hasError=TRUE;
				}				
				decimalSeparatorFound=TRUE;
			}
			if(isNumber(*currentChar)){
				numberFound=TRUE;
			}			
			if(!(*currentChar=='+' || *currentChar=='-' || *currentChar=='.' || isNumber(*currentChar))){
				//caracter invalido para un numero
				EM_RegisterError(ls->EM,ls->module,EM_LEXICAL,"formato de numero incorrecto, se encontro un caracter no valido en numeros.",token->row,token->column);
				token->hasError=TRUE;
			}
		}
		if(!token->hasError){
			if(numberFound==FALSE ){
				EM_RegisterError(ls->EM,ls->module,EM_LEXICAL,"formato de numero incorrecto, no se encontro un numero.",token->row,token->column);
				token->hasError=TRUE;
			}			
			if(decimalSeparatorFound){
				token->literalType=LITERALTYPE_REAL;
				//To Do:Validar tamaños maximos, modificar token->hasError en caso de error y registrar el mismo en el ErrorManager
			}else{
				token->literalType=LITERALTYPE_INTEGER;
				
				if(plusOrNegSign){
					number=cloneStr(token->value+1);
				}else{
					number=cloneStr(token->value);
				}

				if(!isIntInRange(number,4294967295)){
					EM_RegisterError(ls->EM,ls->module,EM_LEXICAL,"entero fuera de rango.",token->row,token->column);
				}
				FREE(number);
				//To Do:Validar tamaños maximos, modificar token->hasError en caso de error y registrar el mismo en el ErrorManager
			}
		}
	}
	return result;
}

void setTokenType(Lexer *ls,Token *token){
/*Hacer que este metodo reciba un token. 
En cada caso, si es posible detectar el tokenType segun lo que hay en el value, devolver true segun correspona. Por ejemplo: Puede ser que recibas variableÑ, en ese caso devolves TRUE en isIdentifier() pero a su vez registras el error en el ErrorManager para decir que no es un caractér valido como identificador. En caso de error ademas de registrarlo hay que setearl token->error  = TRUE; */
	if(isStringConstant(ls,token) || isNumericLiteral(ls,token)){
		token->type = TYPE_CONSTANT;
	}else{
		if(isOperator(*token->value)){
			token->type = TYPE_OPERATOR;
		}else{
			if(isKeyword(token->value,token->lenght)){
				token->type = TYPE_KEYWORD;
			}else{
				if(isSeparator(*token->value)){
					token->type = TYPE_SEPARATOR;
				}else{
					if(isCommentToken(token->value,token->lenght)){
						token->type = TYPE_COMMENT;
					}else{
						if(*token->value==EOSTR){
							token->type = TYPE_EOF;
						}else{
							if(isIdentifier(ls,token)){
								token->type = TYPE_IDENTIFIER;
							}else{
								EM_RegisterError(ls->EM,ls->module,EM_LEXICAL,"Token invalido.",token->row,token->column);
								/*To Do:
								Acá debe setear al token->error  = TRUE;								
								*/
								token->type = TYPE_UNKNOWN;								
							}
						}
					}
				}				
			}			
		}
	}
	return;
}

int isTheTokensEnd(Lexer *ls,Token *token){
	int scapeCharacterCount,i;
	int result;
	int isLastChar=0;
	char *firstTokenChar;
	char test;
	test='\r';
	firstTokenChar=token->value;
	isLastChar=(ls->nextChar==EOSTR);
	if(*(token->value)=='"'){
		//Si es string
		if(token->lenght ==1){
			return FALSE;
		}else{
			if(!isLastChar){
				//Aca sé que el largo del token es de 2 en adelante
				if(ls->currentChar=='"'){
					//veo si la comilla está escapada o no, para eso cuento la cantidad de caracteres de escape consecutivos desde el caracter actual hacia atras
					scapeCharacterCount=0;
					for(i=token->lenght-2;*(firstTokenChar+i)=='\\';i--){//corto cuando el caracter deja de ser de escape							
						scapeCharacterCount++;
					}
					/*Si la cantidad de caracteres de escape consecutivos es Par  ->Final de la cadena
					  Si la cantidad de caracteres de escape consecutivos es Impar->Comilla escapada*/
					result=(scapeCharacterCount % 2 ==0);//Devuelve TRUE si es Par
					return result;
				}else{
					if(ls->nextChar=='\n' || ls->nextChar=='\r'){
						return TRUE;
					}else{
						return FALSE;
					}
				}
			}else{
				return TRUE;
			}
		}
	}else{		
		if(!isLastChar){
			//Está permitido ver que hay en nextChar porque no es el ultimo char
			if(token->lenght>=2){
				if(*firstTokenChar=='/' && *(firstTokenChar+1)=='/'){//es un comentario
					return (ls->nextChar=='\n');//cierra el comentario con un fin de linea
				}
			}
			if(isSeparator(ls->currentChar)){
				return TRUE;
			}
			if(isSeparator(ls->nextChar)){
				return TRUE;
			}
			if(ls->nextChar=='"'){
				return TRUE;
			}
		}else{
			return TRUE;
		}
		return FALSE;
	}
}

int updateRowAndColumn(char character,int *column, int *row){
	if(character=='\t'){
		(*column)+=3;
	}else{
		if(character=='\r'){
			(*column)=1;
		}else{
			(*column)++;
		}
	}
	if(character=='\n'){
		(*column)=1;
		(*row)++;
	}
	return 0;
}


void internal_destroyToken(Token* token){
	if(token==NULL){
		return;
	}
	if(token->value!=NULL){
		FREE(token->value);
	}	
	FREE(token);
	token=NULL;
	return;
}

void destroyToken(Token* token){
	if(token==NULL){
		return;
	}

	//printf("D_Token: %s\n",token->value);	

	if(token->value!=NULL){
		FREE(token->value);
	}	
	FREE(token);
	token=NULL;
	return;
}

/* Crea una instancia de la estructura Lexer*/
Lexer* createLexer(const char* filePath,ErrorManager *EM, int module){
	Lexer* ls;
	ls=(Lexer*)MALLOC(Lexer,1);
	ls->column=1;
	ls->row=1;
	ls->module=module;
	ls->EOFILE=0;
	ls->currentChar=EOSTR;
	ls->nextChar=EOSTR;
	ls->file=fopen(filePath, "r");
	ls->EM=EM;
	ls->fileValid=TRUE;
	if(ls->file==NULL){
		EM_RegisterError(ls->EM,ls->module,EM_LEXICAL,"no se puede abrir el archivo",ls->row,ls->column);
		ls->EOFILE=TRUE;
		ls->fileValid=FALSE;
	}else{
		ls->nextChar=getc(ls->file);
		if(ls->nextChar==EOF){
			EM_RegisterError(ls->EM,ls->module,EM_LEXICAL,"Archivo vacio.",ls->row,ls->column);
			ls->nextChar=EOSTR;
			ls->EOFILE=TRUE;
		}
	}	
	return ls;
}
Token* createToken(){
	Token* token;
	token=(Token*)MALLOC(Token,1);
	token->type=TYPE_UNKNOWN;
	token->literalType=LITERALTYPE_UNKNOWN;
	token->column=0;
	token->row=0;
	token->value=MALLOC(char,1);;
	token->lenght=0;
	token->tokenWithError=FALSE;
	*token->value=EOSTR;
	token->bufferLenght=0;
	token->hasError=FALSE;
	return token;
}

#define INITIAL_TOKENVALUE_BUFFER_LENGHT 100
void LexerReadChar(Lexer *ls,Token *t){	
	char *aux;
	if(t->lenght>=(t->bufferLenght * INITIAL_TOKENVALUE_BUFFER_LENGHT)){
		aux=t->value;
		t->value=MALLOC(char,t->lenght+INITIAL_TOKENVALUE_BUFFER_LENGHT+1+1);
		memcpy(t->value,aux,t->lenght);
		FREE(aux);
		t->bufferLenght++;
	}
	*(t->value+t->lenght)=ls->currentChar;
	*(t->value+t->lenght+1)=EOSTR;
	t->lenght++;
}

void LexerNextChar(Lexer *ls){
	if(!ls->EOFILE){
		ls->currentChar=ls->nextChar;
		ls->nextChar=getc(ls->file);
		if (ls->nextChar==EOF){
			ls->nextChar=EOSTR;
			//ls->EOFILE=TRUE;
		}
	}else{
		ls->currentChar=ls->nextChar;
		ls->nextChar=EOSTR;
	}
}


Token* _getNextToken(Lexer *ls){
	int exit=0;
	int forceTokenBegining=TRUE;
	char *aux;
	
	Token* token=createToken();
	do{
		LexerNextChar(ls);
		LexerReadChar(ls,token);
		if(ls->currentChar==EOSTR){
			token->type=TYPE_EOF;
			*token->value=EOSTR;
			ls->EOFILE=TRUE;
		}else{
			if(forceTokenBegining){
				//Empieza un token
				forceTokenBegining=FALSE;
				token->column=ls->column;
				token->row=ls->row;
			}
			if(isTheTokensEnd(ls,token)){
				//Termina un token
				forceTokenBegining=TRUE;
				exit=TRUE;
			}
			updateRowAndColumn(ls->currentChar,&ls->column,&ls->row);
		}
	}while(ls->currentChar!=EOSTR && exit==FALSE && !ls->EOFILE);
	setTokenType(ls,token);

	//Ajusto la memoria alocada para que buffer se adecue al minimo necesario para que quepa el value.
	aux=token->value;
	token->value=MALLOC(char,token->lenght+1);
	memcpy(token->value,aux,token->lenght+1);
	FREE(aux);
	return token;
}

Token *getNextToken_TrashOptional(Lexer *ls,int omitTrash){
	Token* token;
	int getNext;
	if(omitTrash){
		getNext=0;
		do{
			getNext=0;
			token=_getNextToken(ls);
			if(token->type==TYPE_SEPARATOR || token->type==TYPE_COMMENT ){
				internal_destroyToken(token);
				getNext=1;
			}else{
				return token;
			}
		}while(getNext && !(ls->EOFILE));
	}else{
		return _getNextToken(ls);
	}
	return 0;
}

Token* getNextToken(Lexer *ls){
	Token *t=getNextToken_TrashOptional(ls,TRUE);

//	printf("C_Token: %s. Modulo: %s.\n",t->value, (ls->module == MODULE_LDM)?"LDM":"LDD");

	return t;
}
