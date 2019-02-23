namespace kore {
	// -- RENDERING TABLE DATA -- //
	template <typename T>
	KORE_EX void renderTable(T* array_in, int array_size)
	{
		for (int i = 0; i < array_size; ++i)
		{
			std::cout << array_in[i] << ", ";
		}
		std::cout << std::endl;
	}
}