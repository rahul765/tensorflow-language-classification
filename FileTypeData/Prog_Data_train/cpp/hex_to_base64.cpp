#include <stdio.h>
#include <iostream>
static const char LUT_E_MIME[64] =
{
    'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H',
    'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P',
    'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X',
    'Y', 'Z', 'a', 'b', 'c', 'd', 'e', 'f',
    'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n',
    'o', 'p', 'q', 'r', 's', 't', 'u', 'v',
    'w', 'x', 'y', 'z', '0', '1', '2', '3',
    '4', '5', '6', '7', '8', '9', '+', '/'
};

static string b64_encode(const char* table,
	                     const uint8_t* data,
	                     size_t size) {

  string r;
  uint32_t a;
  int i;

  size_t nSets = dSize / 3;
  size_t nExtra = dSize % 3;
  size_t end = nSets * 3;
  r.reserve( (nSets * 4) + 4 );

  for(int i=0; i<end; i+=3) {
  	
  }
}