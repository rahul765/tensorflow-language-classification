#include <iostream>
#include <conio.h>

using namespace std;

struct lnode
{
       int data;
       node *ptr;
       };
       
struct bnode
{
       int data;
       node *left;
       node *right;
       };

int main()
{
    lnode l = new lnode();
    l->data = 10;
    l->ptr = new node();
    l->ptr->data = 20;
    l->ptr->ptr = new node();
    l->ptr->ptr->data = 30;
    l->ptr->ptr->ptr = new node();
    l->ptr->ptr->ptr->data = 40;
    l->ptr->ptr->ptr->ptr = new node();
    l->ptr->ptr->ptr->ptr->data = 50;
    l->ptr->ptr->ptr->ptr->ptr = null;
    cout<<getch();
    return 0;
}
