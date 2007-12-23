#include "stdio.h"
#include "stdlib.h"
#include "string.h"
#include "math.h"

typedef struct circle {
       float x;
       float y;
       float radio;
       char* name; 
}Circle;

typedef struct point {
	float x;
	float y;
}Point;

typedef struct line {
	Point pFrom;
	Point pTo;
}Line;

typedef struct nodo {
	Circle* circle;
	struct nodo* next;
}Nodo;

typedef struct lista {
	Nodo* primero;
	Nodo* actual;
}Lista;

/*********************************************************/
Lista* listaCreate(void){
       Lista *lista;
       lista = (Lista*)malloc(sizeof(Lista));
       lista->actual=NULL;
       lista->primero=NULL;
       return lista;
}
/*********************************************************/
void listaAgregar(Lista* list, Circle* circle){
    Nodo *cte,*aux;
    Nodo* nodo= (Nodo*)malloc(sizeof(Nodo));
    nodo->circle=circle;
    nodo->next=NULL;
    if (list->primero==NULL){
       list->primero=nodo;
       list->actual=nodo;
    }else{
          cte = list->primero;
          aux = cte;
          while ((cte != NULL)&&(cte->circle->radio > nodo->circle->radio)){
                aux = cte;
                cte = cte->next;
          }
          if(cte==NULL){ //insertar en el ultimo lugar
             list->actual->next=nodo;
             list->actual=nodo;
          }else if(cte==list->primero){ //insertar en primer lugar
             nodo->next=list->primero;
             list->primero=nodo;
          }else{
             nodo->next=cte;
             aux->next=nodo;
          }
    }
}
/*********************************************************/
void showCircles(Lista *lista){
     Nodo *node;
     node = lista->primero;
     while (node != NULL){
           printf("%s %f\n",node->circle->name,node->circle->radio);
           node=node->next;
     }
}
/*********************************************************/
void listaEliminar(Lista* lista){
     Nodo *node,*aux;
     node=lista->primero;
     while (node != NULL){
           free(node->circle->name);
           free(node->circle);
           aux=node;
           node=node->next;
           free(aux);
     }
     free(lista);
}
/*********************************************************/
int getToken(FILE* file, char* buffer, char token){
    char aux;
    int pos=0;
    aux= getc(file);
    while ((aux!=EOF)&&(aux!='\n')&&((aux!=token))){
          buffer[pos]=aux;
          pos++;
          aux= getc(file);
    }
    buffer[pos]=0;
    return pos;
}
/*********************************************************/
Circle* getNextCircle(FILE* file){
    Circle* circle= (Circle*)malloc(sizeof(Circle));
    char buffer[50];
    float resultado;

    resultado=getToken(file,buffer,' ');
    if (resultado==0){
       free(circle);
       return NULL;
    }else{
       circle->x=atof(buffer);
    }
    
	resultado=getToken(file,buffer,' ');
    if (resultado==0){
       free(circle);
       return NULL;
    }else{
       circle->y=atof(buffer);
    }
    
	resultado=getToken(file,buffer,' ');
    if (resultado==0){
       free(circle);
       return NULL;
    }else{
       circle->radio=atof(buffer);
    }
    
	resultado=getToken(file,buffer,' ');
    if (resultado==0){
       free(circle);
       return NULL;
    }else{
       circle->name=(char*)malloc((resultado+1)*sizeof(char));
       strcpy(circle->name,buffer);
    }
    
	return circle;
}
/*********************************************************/
int escalar(int x1, int x2, int y1, int y2) {
	return ((x1*x2)+(y1*y2));
}

int colisiona(Circle* circle, Line line){
	Point p,q,g,r,X;
	float t,distancia;

	p.x=line.pFrom.x;
	p.y=line.pFrom.y;
	q.x=line.pTo.x;
	q.y=line.pTo.y;
	g.x=q.x-p.x;
	g.y=q.y-p.y;
	r.x=circle->x;
	r.y=circle->y;

	t = (g.x*(r.x-p.x)+g.y*(r.y-p.y)) / ((g.x*g.x) + (g.y*g.y));

	if (t>1) t=1;
	if (t<0) t=0;

	X.x = t*g.x+p.x;
	X.y = t*g.y+p.y;

	distancia = sqrt(pow(X.x-r.x,2)+pow(X.y-r.y,2));

	return distancia<=circle->radio;
}
/*********************************************************/
int main(int argc, char** argv) { 
	Lista* lista;
	FILE* file;
	Line line;
	Circle* circle;
	
    if(argc!=5){
        printf("Cantidad erronea de parametros.\n");
        return 1;                
    }

	line.pFrom.x=atof(argv[1]);
	line.pFrom.y=atof(argv[2]);
	line.pTo.x=atof(argv[3]);
	line.pTo.y=atof(argv[4]);

	file=fopen("Circulos.txt", "r");
	
	if (file==NULL)
		return 1;

	lista = listaCreate();

	circle=getNextCircle(file);
	
	while(circle!=NULL) {
		
		if (colisiona(circle, line))
			listaAgregar(lista,circle);
		else {
			free(circle->name);
			free(circle);
		}
		circle = getNextCircle(file);
	}
	
    showCircles(lista);
    listaEliminar(lista);
    fclose(file);

	printf("\n\n");


	return 1;
}
