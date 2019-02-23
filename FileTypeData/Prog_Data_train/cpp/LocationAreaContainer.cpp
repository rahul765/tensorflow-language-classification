
#include "LocationAreaContainer.hpp"

using namespace std;

unsigned int LocationAreaContainer::size() const {
    return locationAreas.size();
}

const std::string& LocationAreaContainer::operator[](const unsigned int i) const{
    return locationAreas[i];
}

