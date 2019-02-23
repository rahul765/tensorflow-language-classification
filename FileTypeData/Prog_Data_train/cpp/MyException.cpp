#include "SymbolNotFoundException.hpp"

const char* SymbolNotFoundException::what() const throw()
{
    return "Exception Error: This symbol '>' wasn't found in [DestinationFilter::getRawDestinationPartOfTheLine].";
}
