#include "TextureClass.h"



TextureClass::TextureClass()
{
	texture = NULL;
}

TextureClass::TextureClass(const TextureClass &)
{
}


TextureClass::~TextureClass()
{
}

bool TextureClass::Initialize(ID3D11Device * device, WCHAR * filename)
{
	HRESULT result;

	result = CreateDDSTextureFromFile(device, filename, NULL, &texture);
	if ( FAILED(result))
	{
		return false;
	}

	return true;
}

void TextureClass::Shutdown()
{
	if (texture)
	{
		texture->Release();
		texture = NULL;
	}
}

ID3D11ShaderResourceView * TextureClass::GetTexture()
{
	return texture;
}
