namespace kore {


	// -- QUICK SORT -- //
	template <typename T>
	KORE_EX int partition(T* array_in, int L, int R)
	{
		int i = L;
		int j = R + 1;
		T v = array_in[L];

		while (true)
		{
			while (array_in[++i] < v);

			while (array_in[--j] > v);

			if (i >= j) break;

			int temp = array_in[j];
			array_in[j] = array_in[i];
			array_in[i] = temp;
		}

		int temp = array_in[j];
		array_in[j] = array_in[L];
		array_in[L] = temp;

		return j;
	}

	template <typename T>
	KORE_EX void quickSort(T* array_in, int L, int R)
	{
		if (L >= R) return;
		int p = partition(array_in, L, R);
		quickSort(array_in, L, p - 1);
		quickSort(array_in, p + 1, R);
	}
}