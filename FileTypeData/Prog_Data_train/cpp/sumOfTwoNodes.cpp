#include <iostream>
#include <conio.h>

using namespace std;

struct node 
{
       int val;
       node *left;
       node *right;
       };

node * NewNode(int val)
{
    node *tmp = new node();
    tmp->val = val;
    tmp->right = tmp->left =NULL;
    return tmp;
}
 
 
bool isPairPresent(node *root,int n)
{
     return true;
 }

int main()
{
    /*
                   15
                /     \
              10      20
             / \     /  \
            8  12   16  25    */
    struct node *root =  NewNode(15);
    root->left = NewNode(10);
    root->right = NewNode(20);
    root->left->left = NewNode(8);
    root->left->right = NewNode(12);
    root->right->left = NewNode(16);
    root->right->right = NewNode(25);
 
    int target = 33;
    if (isPairPresent(root, target) == false)
        printf("\n No such values are found\n");
    cout<<getch();
    return 0;
}
