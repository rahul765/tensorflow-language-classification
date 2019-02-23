
#include "ImsiContainer.hpp"

using namespace std;

unsigned int ImsiContainer::size() const {
    return imsis.size();
}

const std::string& ImsiContainer::operator[](const unsigned int i) const{
    return imsis[i];
}

