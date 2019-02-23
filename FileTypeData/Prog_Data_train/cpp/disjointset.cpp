#include<stdio.h> 
#include<malloc.h>
struct node 
{
  int data;
  struct node *next;     
};


struct node * insert(struct node **p,int data1)
{
   int i;
   struct node *newnode;
   newnode=(struct node*)malloc(sizeof(struct node));
   newnode->data=data1;
                        
  
   newnode->next=*p;
   *p=newnode;
   
 return *p;
} 
show(struct node *head1 )
{
                        while(head1!=NULL)
                    {
                       
 		                   printf("%d\n",head1->data);
                            head1=head1->next;
                     
                    }
  
  
}
  main()
{
      int i,c,e;
      struct node *head;        
      head=NULL;
      c=0;
      while(c!=3)
      {
      printf("choice\n");

      scanf("%d",&c); 
      if(c==1)
     {
      printf("enter the  linked list ele\n");
      scanf("%d",&e);
      head=insert(&head,e);
      }
      else if(c==2)
      show(head);
      }
      scanf("%d",&i);            
}
