namespace kore {
	// -- ABSOLUTE VALUE -- //
	template <typename T>
	KORE_EX T absolute(T& value)
	{
		if (value > 0)
			return value;
		else
			return -value;
	}
}