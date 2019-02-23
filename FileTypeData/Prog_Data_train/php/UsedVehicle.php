<?php namespace App;

use App\Repositories\CommonModelMethods;
use Illuminate\Database\Eloquent\Model;

class UsedVehicle extends Model {

    use CommonModelMethods;
    protected $relation_searchable = array(
        'bname' => 'vehicles' ,
        'model' => 'vehicles',
        'variant' => 'vehicles'
    );
    protected $searchable = array('id', 'brand', 'model', 'variant');
    protected $ignore_regex = array('id', 'status', 'dealer_id');

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'used_vehicles';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['vehicle_id','dealer_id','transmission_type','fuel_type','colour','model_year','kilometers','number_of_owners', 'description', 'registered_at','price','insurance','country','state','city','location','address','pincode','status'];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = array(
        'price'     => 'float',
        'insurance' => 'boolean',
    );

    /**
     * Get the vehicle associated with the given used vehicle.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function vehicle(){
        return $this->belongsTo(Vehicle::class);
    }

    /**
     * Get the dealer associated with the given used vehicle.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function dealer(){
        return $this->belongsTo(Dealer::class);
    }

    /**
     * Get all the pictures associated with the used vehicle
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function pictures()
    {
        return $this->morphMany('App\Picture', 'picturable');
    }
}