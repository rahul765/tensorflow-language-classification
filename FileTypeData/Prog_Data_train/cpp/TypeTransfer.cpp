//get the RGBA from a mat.   this may be used frequently during Android
unsigned char* MatToRGBA(const Mat *mat)
{
	int width = mat->cols * mat->channels();
	int height = mat->rows;
	int len  = width * height;
	unsigned char *rgba = (unsigned char*)malloc(mat->cols * mat->rows * 4);
	unsigned char * p = mat->data;
	for(int i = 0, j = 0; i < len; i +=3, j+=4)
	{
		rgba[j] = p[i];
		rgba[j+1] = p[i+1];
		rgba[j+2] = p[i+2];
		rgba[j+3]=255;
	}

	cv::Mat img(mat->rows, mat->cols, CV_8UC4, rgba);
	cv::imwrite("rgba.jpg",img);
	return rgba;
}

//get the specified channel of the mat
unsigned char* GetMatChannel(const Mat *mat, const int chal)
{
	int width = mat->cols * mat->channels();
	int height = mat->rows;
	int len  = width * height;
	unsigned char *rgba = (unsigned char*)malloc(mat->cols * mat->rows);
	unsigned char * p = mat->data;
	p += chal;
	for(int i = 0, j = 0; i < len; i +=3, ++j)
	{
		rgba[j] = p[i];
	}

	cv::Mat img(mat->rows, mat->cols, CV_8UC1, rgba);
	cv::imwrite("channel.jpg",img);
	return rgba;
}