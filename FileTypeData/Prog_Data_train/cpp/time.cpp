#include <bts/blockchain/time.hpp>

namespace bts { namespace blockchain {
static int32_t adjusted_time_sec = 0;

void advance_time( int32_t delta_seconds )
{
  adjusted_time_sec += delta_seconds;
}

fc::time_point_sec now()
{
   return fc::time_point::now() + fc::seconds( adjusted_time_sec );
}
} } // bts::blockchain
