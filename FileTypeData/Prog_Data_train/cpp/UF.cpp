#include "UF.h"

UF::UF(int V) {
    this->V = V;
    id = new int[V];
    for (int i = 0; i < V; i++) {
        id[i] = i;
    }  
}

UF::~UF() {
    delete id;
}

int UF::root(int v) {
   if (v >= this->V) return -1;
   int i = this->id[v];
   while(i != id[i]) {
        // make every child the child of its grand parent
        id[i] = id[id[i]];
        i = id[i];
   }
   return i;
}
    
void UF:: __union(int v, int w) {
    int i = this->root(v);
    int j = this->root(w);

    id[i] = j;
}

bool UF::__find(int v, int w) {
    return (this->root(v) == this->root(w));
}
