#include <iostream>
#include <conio.h>

using namespace std;


struct node
{
       int data;
       node *left;
       node *right;
       };
     
     
       
bool checkBST(node *root,int min,int max)
{
     if(root == NULL)
     return true;
     if(root->data < min || root->data >max)
     return false;
     return checkBST(root->left,min,root->data) && checkBST(root->right,root->data,max);
 };       

int main()
{
    node *root = new node();
    root->data = 10;
    root->left = new node();
    root->left->data = 5;
    root->left->left = new node();
    root->left->left->data = 1;
    root->right = new node();
    root->right->data = 20;
    root->right->left = new node();
    root->right->left->data = 15;
    root->right->right = new node();
    root->right->right->data = 5;
    int  min = INT_MIN;
    int max = INT_MAX;
    cout<<"tree is BST "<<checkBST(root,min,max);
    cout<<getch();
    return 0;
}
