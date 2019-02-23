namespace kore {
	// -- SUM OF TABLE ELEMENTS -- //
	template <typename T>
	KORE_EX int sumOfTableElements(T* table, const int tab_elements)
	{
		int result = 0;

		for (int i = 0; i < tab_elements; ++i)
		{
			result += table[i];
		}

		return result;
	}
}