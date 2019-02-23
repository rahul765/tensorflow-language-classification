<?php

namespace App\Repositories\Eloquent;

use App\OnRoadPrice;
use App\Repositories\BaseRepository;
use App\Repositories\OnRoadPriceRepository;
use App\Vehicle;
use Validator;

class EloquentOnRoadPriceRepository extends BaseRepository implements OnRoadPriceRepository {

    protected $model;

    /**
     * @param Vehicle $model
     */
    function __construct(OnRoadPrice $model) {
        $this->model = $model;
    }
    /**
     * Get all the user by searching having regex for string.
     *
     * * @return array(User)
     */
   /* public function regexSearchFrontend($columns, $size, $sorts = null) {
        if(isset($columns) && is_array($columns)) {
            $columns = array_filter($columns);
        }
        if(isset($columns['dealer-name'])) {
            $columns['name'] = $columns['dealer-name'];
            unset($columns['dealer-name']);
        }
        $relation_searchable = array(
            'bname'     => 'vehicles' ,
            'model'     => 'vehicles',
            'variant'   => 'vehicles',
            'name'      => 'dealers'
        );
        $ignore_regex = array('id', 'status','dealer_id');
        $searchable = array('name', 'bname', 'model', 'variant','model_year','dealer_id','colour','model_year','kilometers','price','insurance','country','state','city','location','address','pincode','status');
        $boolean_keys = array('insurance');
        $integer_keys = array('price');
        $columns = array_intersect_key($columns, array_flip($searchable));

        $model_search = $this->model->select('used_vehicles.*');

        $model_search->with('vehicle', 'dealer');
        $model_search->select('used_vehicles.*');
        $model_search->join('vehicles', 'used_vehicles.vehicle_id', '=', 'vehicles.id');
        if(isset($columns['name'])) {
            $model_search->join('dealers', 'used_vehicles.dealer_id', '=', 'dealers.id');
        }
        if (isset($columns)) {
            if(is_array($columns)) {
                foreach($columns as $key => $values) {
                    $key_name = "used_vehicles.$key";
                    if(isset($relation_searchable) && array_key_exists($key, $relation_searchable)) {
                        $key_name = $relation_searchable[$key] . ".$key";
                    }
                    if(is_array($values)) {
                        if(count($values) > 0) {
                            $model_search->where(function($query) use ($key, $key_name, $values, $integer_keys, $boolean_keys, $ignore_regex){
                                foreach($values as $value) {
                                    if (in_array($key, $boolean_keys)) {
                                        $value = filter_var($value, FILTER_VALIDATE_BOOLEAN);
                                        $query->orWhere($key_name, '=', $value);
                                    } else if(in_array($key, $integer_keys)){
                                        $query->orWhere($key_name, '=', $value);
                                    } else {
                                        if(in_array($key, $ignore_regex)) {
                                            $query->orWhere($key_name, '=', $value);
                                        } else {
                                            $query->orWhere($key_name, 'LIKE', "%$value%");
                                        }
                                    }
                                }
                            });
                        }
                    } else if($values != '' && in_array($key, $boolean_keys)){
                        $values = filter_var($values, FILTER_VALIDATE_BOOLEAN);
                        $model_search->where($key_name, '=', $values);
                    } else if($values != '' && in_array($key, $integer_keys)) {
                        $model_search->where($key_name, '=', $values);
                    } else if($values != ''){
                        if(in_array($key, $ignore_regex)) {
                            $model_search->where($key_name, '=', $values);
                        } else {
                            $model_search->where($key_name, 'LIKE', "%$values%");
                        }
                    }
                }
            }
        }
        if(isset($sorts)) {
            foreach ($sorts as $key => $value) {
                $model_search->orderBy($key, empty($value) ? 'asc' : $value);
            }
        }
        return $model_search->paginate($size);
    }*/

    /**
     * Get all the user by searching having regex for string.
     *
     * * @return array(User)
     */
    /*public function searchFrontend($columns, $size, $sorts = null) {
        if(isset($columns) && is_array($columns)) {
            $columns = array_filter($columns);
        }
        if(isset($columns) && is_array($columns)) {
            foreach ($columns as $key => $value) {
                if(is_array($value)) {
                    $value = array_filter($value);
                    if (empty($value)) {
                        unset($columns[$key]);
                    }
                }
            }
        }
        if(isset($columns['dealer-name'])) {
            $columns['name'] = $columns['dealer-name'];
            unset($columns['dealer-name']);
        }
        $relation_searchable = array(
            'bname'                         => 'vehicles' ,
            'model'                         => 'vehicles',
            'variant'                       => 'vehicles',
            'name'                          => 'dealers',
            'power_windows'                 => 'vehicles',
            'abs'                           => 'vehicles',
            'rear_defogger'                 => 'vehicles',
            'inbuilt_music_system'          => 'vehicles',
            'sunroof_moonroof'              => 'vehicles',
            'anti_theft_immobilizer'        => 'vehicles',
            'steering_mounted_controls'     => 'vehicles',
            'rear_parking_sensors'          => 'vehicles',
            'rear_wash_wiper'               => 'vehicles',
            'seat_upholstery'               => 'vehicles',
            'adjustable_steering'           => 'vehicles',
            'transmission_type'             => 'vehicles',
            'body_type'                     => 'vehicles',
            'fuel_type'                     => 'vehicles',
            'drive_type'                    => 'vehicles',
        );
        $apply_regex = array(
            'transmission_type', 'fuel_type'
        );
        $must_self = array(
            'transmission_type', 'fuel_type'
        );
        $searchable = array('name', 'bname', 'model', 'variant','model_year','dealer_id','colour','model_year','kilometers','price','insurance','country','state','city','location','address','pincode','power_windows','abs','rear_defogger','inbuilt_music_system','sunroof_moonroof','anti_theft_immobilizer','steering_mounted_controls','rear_parking_sensors','rear_wash_wiper','seat_upholstery','adjustable_steering','transmission_type','body_type','fuel_type','drive_type','status');
        $columns = array_intersect_key($columns, array_flip($searchable));

        $model_search = $this->model->select('used_vehicles.*');

        $model_search->with('vehicle', 'dealer');
        $model_search->select('used_vehicles.*');
        $model_search->join('vehicles', 'used_vehicles.vehicle_id', '=', 'vehicles.id');
        if(isset($columns['name'])) {
            $model_search->join('dealers', 'used_vehicles.dealer_id', '=', 'dealers.id');
        }
        if (isset($columns)) {
            if(is_array($columns)) {
                foreach($columns as $key => $values) {
                    $self_key_name = null;
                    if(in_array($key, $must_self)) {
                        $self_key_name = "used_vehicles.$key";
                    }
                    $key_name = "used_vehicles.$key";
                    $operator = '=';
                    if(isset($relation_searchable) && array_key_exists($key, $relation_searchable)) {
                        $key_name = $relation_searchable[$key] . ".$key";
                    }
                    if(is_array($values)) {
                        if(count($values) > 0) {
                            $model_search->where(function($query) use ($key, $key_name, $values, $operator, $apply_regex, $self_key_name){
                                foreach($values as $value) {
                                    if(!empty($self_key_name) && $self_key_name != $key_name) {
                                        if(in_array($key, $apply_regex)) {
                                            $query->orWhere($self_key_name, 'LIKE', "%$value%");
                                        } elseif($key == 'price' && strpos($value, '-')) {
                                            $split_values = explode('-', $value);
                                            $query->whereBetween($self_key_name, $split_values);
                                        } else {
                                            $query->orWhere($self_key_name, $operator, $value);
                                        }
                                    }

                                    if(in_array($key, $apply_regex)) {
                                        $query->orWhere($key_name, 'LIKE', "%$value%");
                                    } elseif($key == 'price' && strpos($value, '-')) {
                                        $split_values = explode('-', $value);
                                        $query->whereBetween($key_name, $split_values);
                                    } else {
                                        $query->orWhere($key_name, $operator, $value);
                                    }
                                }
                            });
                        }
                    } else if($values != ''){
                        if(!empty($self_key_name) && $self_key_name != $key_name) {
                            if(in_array($key, $apply_regex)) {
                                $model_search->where($key_name, 'LIKE', "%$values%");
                            } elseif($key == 'price' && strpos($values, '-')) {
                                $split_values = explode('-', $values);
                                $model_search->whereBetween($key_name, $split_values);
                            } else {
                                $model_search->where($key_name, $operator, $values);
                            }
                        }
                        if(in_array($key, $apply_regex)) {
                            $model_search->where($key_name, 'LIKE', "%$values%");
                        } elseif($key == 'price' && strpos($values, '-')) {
                            $split_values = explode('-', $values);
                            $model_search->whereBetween($key_name, $split_values);
                        } else {
                            $model_search->where($key_name, $operator, $values);
                        }
                    }
                }
            }
        }
        if(isset($sorts)) {
            foreach ($sorts as $key => $value) {
                $model_search->orderBy($key, empty($value) ? 'asc' : $value);
            }
        }
        return $model_search->paginate($size);
    }*/
}