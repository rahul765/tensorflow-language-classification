namespace kore {

	// -- INDUCTION -- //
	KORE_EX int induction(int steps)
	{
		int result = 0;

		if (steps == 1)
			return 1;
		if (steps <= 0)
			return 0;

		result = (steps * (steps + 1)) / 2;
		return result;
	}
}