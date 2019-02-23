#include <cstdio>
#include <ctime>
#include <algorithm>
#include <string>

#include "sse2/checkdigit.h"

auto main(int argc, const char *argv[]) -> int {
	char val[17];
	unsigned int year;

	{
		auto now = std::time(nullptr);
		std::tm tm_now;
		localtime_r(&now, &tm_now);
		year = tm_now.tm_year + 1900;
	}

	for (;;) {
		std::fill(val + 10, val + 17, 1);
		if (std::scanf("%16s", val) <= 0) return 1;
		if (!isdigit(val[0])) break;
		if (!val[10] || !val[12]) {
			std::printf("'%s' is a%scorrect INN\n", val,
					check_inn(val, val[10] ? 12 : 10) ?
							" " : "n in");
		} else if (!val[13]) {
			std::printf("'%s' is a%scorrect OGRN\n", val,
					check_ogrn(val, 13, year) ? " " : "n in");
		} else if (!val[15]) {
			std::printf("'%s' is a%scorrect OGRNIP\n", val,
					check_ogrn(val, 15, year) ? " " : "n in");
		} else {
			std::printf("Unknown identifier: '%s'\n", val);
			break;
		}
	}

	return 0;
}
