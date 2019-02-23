namespace kore {
	// -- SELECTION SORT -- //
	template <typename T>
	KORE_EX T selectionSort(T* array_in, int array_size)
	{

		int i = 0;
		int j = 0;
		int smaller_element = 0;

		for (i; i < array_size - 1; ++i)
		{
			smaller_element = i;

			for (j = i + 1; j < array_size; ++j)
			{
				if (array_in[j] < array_in[smaller_element])
				{
					smaller_element = j;
				}
			}

			T temp = array_in[i];
			array_in[i] = array_in[smaller_element];
			array_in[smaller_element] = temp;
		}
	}
}