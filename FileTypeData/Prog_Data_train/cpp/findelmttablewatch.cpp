namespace kore {
	// -- FIND ELEMENT IN TABLE WITH WATCHER -- //
	template <typename T>
	KORE_EX bool findWithWatcher(T* table, const int tab_elements, T search)
	{
		bool found = false;
		table[tab_elements] = search;
		int index = 0;

		while (table[index] != search)
		{
			index++;

			if (index == tab_elements)
				return found;
		}
	}
}