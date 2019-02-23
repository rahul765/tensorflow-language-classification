
#include "CellIdContainer.hpp"

using namespace std;

unsigned int CellIdContainer::size() const {
    return cellIds.size();
}

const std::string& CellIdContainer::operator[](const unsigned int i) const {
    return cellIds[i];
}

