<?php

namespace App\Repositories\Eloquent;

use App\Brand;
use App\Picture;
use App\Repositories\BaseRepository;
use App\Repositories\BrandRepository;
use App\Repositories\VehicleRepository;
use App\Vehicle;
use Validator;

class EloquentVehicleRepository extends BaseRepository implements VehicleRepository {

    protected $model;
    protected $brand_model;

    /**
     * @param Vehicle $model
     */
    function __construct(Vehicle $model, Brand $brand_model) {
        $this->model = $model;
        $this->brand_model = $brand_model;
    }

    public function create($columns)
    {
        $brand = $this->brand_model->find($columns['brand_id']);
        $columns['bname'] = $brand->name;
        return parent::create($columns);
    }

    public function update($resource, $columns)
    {
        if($resource->brand_id != $columns['brand_id']) {
            $brand = $this->brand_model->find($columns['brand_id']);
            $columns['bname'] = $brand->name;
        }
        return parent::update($resource, $columns);
    }

    /*public function regexSearch($columns, $size)
    {
        $brand = $columns['brand'];
        $vehicles = $this->model->regexSearch($columns)->where(function($query) use ($brand){
            if(!empty($brand)) {

            }
        })->paginate($size);
        dd($vehicles);
        return $this->model->regexSearch($columns)->paginate($size);
    }*/


    public function attachThumbnail($vehicle, $thumbnail) {
        if (isset($thumbnail) && $thumbnail->isValid()) {
            $basePath = 'uploads/';
            $destinationPath = 'vehicles'; // upload path
            $extension = $thumbnail->getClientOriginalExtension(); // getting image extension
            $fileName = 'vehicle-thumbnail-' . str_pad($vehicle->id, 10, '0', STR_PAD_LEFT) . '.' . $extension; // renameing image
            $thumbnail->move($basePath . $destinationPath, $fileName); // uploading file to given path

            $vehicle->thumbnail = "$destinationPath/$fileName";
            $vehicle->save();
        }
        return $vehicle;
    }

    public function attachBrochure($vehicle, $brochure) {
        if (isset($brochure) && $brochure->isValid()) {
            $basePath = 'uploads/';
            $destinationPath = 'vehicles'; // upload path
            $extension = $brochure->getClientOriginalExtension(); // getting image extension
            $fileName = 'vehicle-brochure-' . str_pad($vehicle->id, 10, '0', STR_PAD_LEFT) . '.' . $extension; // renameing image
            $brochure->move($basePath . $destinationPath, $fileName); // uploading file to given path

            $vehicle->brochure = "$destinationPath/$fileName";
            $vehicle->save();
        }
        return $vehicle;
    }

    /**
     * Attach galary pictures to resources
     *
     * @param $resource
     * @param $pictures
     * @return mixed
     */
    public function attachPictures($vehicle, $pictures)
    {
        $counter = $vehicle->pictures()?$vehicle->pictures()->count():0;
        if(is_array($pictures)) {
            foreach($pictures as $picture) {
                if (isset($picture) && $picture->isValid()) {
                    $basePath = 'uploads/';
                    $destinationPath = 'vehicle'; // upload path
                    $extension = $picture->getClientOriginalExtension(); // getting image extension
                    $fileName = 'VGP-'. $vehicle->id . '-' . milliseconds() . (++$counter) . '.' . $extension;
                    $picture->move($basePath . $destinationPath, $fileName); // uploading file to given path

                    $pictureEntity = new Picture();
                    $pictureEntity->path = "$destinationPath/$fileName";
                    $pictureEntity->caption = $vehicle->model;
                    $vehicle->pictures()->save($pictureEntity);
                }
            }
        }
        return $vehicle;
    }

    /**
     * Find the vehicle by brand, model and variant
     *
     * @param $brand_id
     * @param $model
     * @param $variant
     * @return mixed
     */
    public function findByBrandModelVariant($brand, $model, $variant, $is_brand_id = true)
    {
        return $this->model->where(function($query) use ($brand, $model, $variant, $is_brand_id){
            if($is_brand_id) {
                $query->where('brand_id', $brand);
            } else {
                $query->where('bname', $brand);
            }
            $query->where('model', $model);
            $query->where('variant', $variant);
       })->first();
    }

    /**
     * Get all the user by searching having regex for string.
     *
     * * @return array(User)
     */
    public function regexSearchFrontend($columns, $size, $sorts = null) {
        if(isset($columns) && is_array($columns)) {
            $columns = array_filter($columns);
        }
        $ignore_regex = array('id', 'status');
        $searchable = array('bname', 'model', 'variant','price','status');
        $boolean_keys = array();
        $integer_keys = array('price');
        $columns = array_intersect_key($columns, array_flip($searchable));

        $model_search = $this->model->select('vehicles.*');

        $model_search->with('pictures');
        $model_search->select('vehicles.*');
        if (isset($columns)) {
            if(is_array($columns)) {
                foreach($columns as $key => $values) {
                    $key_name = "vehicles.$key";
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
    }

    /**
     * Get all the user by searching having regex for string.
     *
     * * @return array(User)
     */
    public function searchFrontend($columns, $size, $sorts = null) {
        if(isset($columns) && is_array($columns)) {
            $columns = array_filter($columns);
        }
        $searchable = array('bname', 'model', 'variant','price','power_windows','abs','rear_defogger','inbuilt_music_system','sunroof_moonroof','anti_theft_immobilizer','steering_mounted_controls','rear_parking_sensors','rear_wash_wiper','seat_upholstery','adjustable_steering','transmission_type','body_type','fuel_type','drive_type','status');
        $columns = array_intersect_key($columns, array_flip($searchable));

        $model_search = $this->model->select('vehicles.*');

        $model_search->with('pictures');
        $model_search->select('vehicles.*');
        if (isset($columns)) {
            if(is_array($columns)) {
                foreach($columns as $key => $values) {
                    $key_name = "vehicles.$key";
                    //$key_name = getColumn($key_name);
                    //$operator = getOperator($key);
                    $operator = '=';
                    if(is_array($values)) {
                        if(count($values) > 0) {
                            $model_search->where(function($query) use ($key, $key_name, $values, $operator){
                                foreach($values as $value) {
                                    if($key == 'price' && strpos($value, '-')) {
                                        $split_values = explode('-', $value);
                                        $query->whereBetween($key_name, $split_values);
                                    } else {
                                        $query->orWhere($key_name, $operator, $value);
                                    }
                                }
                            });
                        }
                    } else if($values != ''){
                        if($key == 'price' && strpos($values, '-')) {
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
    }
}