namespace kore {	
	// -- MAX VALUE IN TABLE -- //
	template <typename T>
	inline KORE_EX T maxValueInTable(T* table, const int tab_elements)
	{
		T result = table[0];
		int i = 0;
		do
		{
			if (table[i] > result)
				result = table[i];
			i++;
		} while (i < tab_elements);

		return result;
	}
}