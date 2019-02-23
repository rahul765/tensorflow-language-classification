namespace kore {
	// -- FIND ELEMENT IN TABLE -- //
	template <typename T>
	KORE_EX bool findElement(T* table, const int tab_elements, T search)
	{
		bool found = false;

		for (int i = 0; i < tab_elements; ++i)
		{
			if (table[i] == search)
				found = true;
		}
		return found;
	}
}