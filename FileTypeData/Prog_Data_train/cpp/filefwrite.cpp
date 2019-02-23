/* fwrite example : write buffer */
#include <stdio.h>

int main ()
{
  FILE * pFile;
  int buffer[] = { 10 , 21 , 34 };
  pFile = fopen ( "input10gb.txt" , "wb" );
  fwrite (buffer , 1 , sizeof(buffer) , pFile );
  fclose (pFile);
  return 0;
}
