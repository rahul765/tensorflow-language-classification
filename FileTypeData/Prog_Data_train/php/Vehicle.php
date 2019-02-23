<?php namespace App;

use App\Repositories\CommonModelMethods;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model {
    use CommonModelMethods;

    protected $searchable = array('id', 'status');
    protected $ignore_regex = array('id', 'status');


    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'vehicles';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['brand_id','bname','model','variant','description','colour','transmission_type','body_type','fuel_type','price','drive_type','seating_capacity','engine_power','power_windows','abs','rear_defogger','inbuilt_music_system','sunroof_moonroof','anti_theft_immobilizer','steering_mounted_controls','rear_parking_sensors','rear_wash_wiper','seat_upholstery','adjustable_steering','brochure','video_script','status'];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'price'                             => 'float',
        'seating_capacity'                  => 'integer',
        'engine_power'                      => 'integer',
        'power_windows'                     => 'boolean',
        'abs'                               => 'boolean',
        'rear_defogger'                     => 'boolean',
        'inbuilt_music_system'              => 'boolean',
        'sunroof_moonroof'                  => 'boolean',
        'anti_theft_immobilizer'            => 'boolean',
        'steering_mounted_controls'         => 'boolean',
        'rear_parking_sensors'              => 'boolean',
        'rear_wash_wiper'                   => 'boolean',
        'seat_upholstery'                   => 'boolean',
        'adjustable_steering'               => 'boolean',
    ];

    /**
     * Get the brand of the associated vehicle.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function brand(){
        return $this->belongsTo('App\Brand');
    }

    /**
     * Get all the pictures associated with the vehicle
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function pictures()
    {
        return $this->morphMany('App\Picture', 'picturable');
    }

    public function onRoadPrices()
    {
        return $this->hasMany('App\OnRoadPrice');
    }
}