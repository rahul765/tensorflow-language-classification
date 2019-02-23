<?php namespace App;

use App\Repositories\CommonModelMethods;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Dealer extends Model {
    use CommonModelMethods;

    protected $searchable = array('id');
    protected $ignore_regex = array('id', 'status');
    protected $geofields = array('geoloc');

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dealers';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['slug','geoloc','email','name','contact_person','about_us','country','state','city','location','address','pincode','website','mobile_number','office_number','status', 'user_id'];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = array();

    /**
     * Get the comments associated with the given dealer.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function user(){
        return $this->belongsTo(User::class);
    }

    /**
     * Get the new vehicles associated with the given dealer.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function newVehicles(){
        return $this->hasMany(NewVehicle::class);
    }

    /**
     * Get the used vehicles associated with the given dealer.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function usedVehicles(){
        return $this->hasMany(UsedVehicle::class);
    }

    /**
     * Get all the pictures associated with the dealer
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function pictures()
    {
        return $this->morphMany('App\Picture', 'picturable');
    }

    /**
     * Get the api keys associated with the given dealer.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function appKeys(){
        return $this->hasMany(AppKey::class);
    }


    public function setGeolocAttribute($value) {
        if(!empty($value)) {
            $this->attributes['geoloc'] = DB::raw("POINT($value)");
        } else {
            $this->attributes['geoloc'] = NULL;
        }
    }

    public function getGeolocAttribute($value){
        if(!empty($value)) {
            $loc = substr($value, 6);
            $loc = preg_replace('/[ ,]+/', ',', $loc, 1);
            return substr($loc, 0, -1);
        } else {
            return '';
        }
    }

    public function newQuery($excludeDeleted = true)
    {
        $raw='';
        foreach($this->geofields as $column){
            $raw .= ' astext('.$column.') as '.$column.' ';
        }

        return parent::newQuery($excludeDeleted)->addSelect('*',DB::raw($raw));
    }

    public function scopeDistance($query, $dist, $geoloc)
    {
        return $query->whereRaw('st_distance(geoloc,POINT('.$geoloc.')) < '.$dist);
    }
}
