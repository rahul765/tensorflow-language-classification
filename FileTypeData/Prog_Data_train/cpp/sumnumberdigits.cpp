namespace kore {


	// -- SUM OF NUMBER DIGITS -- //
	KORE_EX int sumOfNumberDigits(int& number)
	{
		int result = 0;

		while (number != 0)
		{
			result = result + (number % 10);   // e.g. 445 mod 10 => 5
			number = (number / 10);            // e.g. 445 div 10 => 44
		}

		return result;
	}
}