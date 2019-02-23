<?php namespace App;

use App\Repositories\CommonModelMethods;
use Illuminate\Database\Eloquent\Model;

class NewVehicle extends Model {
    use CommonModelMethods;

    protected $relation_searchable = array(
                                        'bname' => 'vehicles' ,
                                        'model' => 'vehicles',
                                        'variant' => 'vehicles'
                                    );
    protected $searchable = array('id', 'brand', 'model', 'variant');
    protected $ignore_regex = array('id', 'status', 'dealer_id');

    protected $table = 'new_vehicles';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['vehicle_id','dealer_id','price','status'];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = array(
        'price'     => 'float',
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
}
