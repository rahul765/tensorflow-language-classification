namespace kore {
	// -- MIN VALUE IN TABLE -- //
	template <typename T>
	KORE_EX T minValueInTable(T* table, const int tab_elements)
	{
		T result = table[0];
		int i = 0;

		for (i; i < tab_elements; ++i)
		{
			if (table[i] < result)
				result = table[i];
		}

		return result;
	}
}