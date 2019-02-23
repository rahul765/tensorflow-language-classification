<?php namespace App;

use App\Repositories\CommonModelMethods;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model {

    use CommonModelMethods;

    protected $searchable = array('id', 'user_id');
    protected $ignore_regex = array('id');
    protected $table = 'inventories';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['registration', 'brand', 'model', 'variant', 'fuel_type', 'model_year', 'colour', 'owner', 'owner_id', 'purchase_date', 'seller_name', 'seller_address', 'seller_contact', 'seller_dealer_name', 'seller_dealer_address', 'seller_dealer_contact', 'purchase_amount', 'expenses', 'selling_date', 'purchaser_name', 'purchaser_address', 'purchaser_contact', 'purchase_dealer_name', 'purchase_dealer_address', 'purchase_dealer_contact', 'sold_amount', 'transfer_date', 'transfer_mediator', 'mediator_contact', 'finance_bank', 'finance_contact', 'finance_amount'];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'purchase_amount'   => 'float',
        'expenses'          => 'float',
        'sold_amount'       => 'float',
    ];

    public function user(){
        return $this->belongsTo('App\User');
    }

    public function pictures()
    {
        return $this->morphMany('App\Picture', 'picturable');
    }

    /**
     * The date attributes that should be casted to carbon.
     *
     * @var array
     */
    protected $dates = ['purchase_date', 'selling_date', 'transfer_date'];

    /**
     * @param $date
     */
    public function setPurchaseDateAttribute($date) {
        if($date) {
            $this->attributes['purchase_date'] = Carbon::createFromFormat('d-m-Y g:i A', $date);
        } else {
            $this->attributes['purchase_date'] = null;
        }
    }

    /**
     * @param $date
     */
    public function setSellingDateAttribute($date) {
        if($date) {
            $this->attributes['selling_date'] = Carbon::createFromFormat('d-m-Y g:i A', $date);
        } else {
            $this->attributes['selling_date'] = null;
        }
    }

    /**
     * @param $date
     */
    public function setTransferDateAttribute($date) {
        if($date) {
            $this->attributes['transfer_date'] = Carbon::createFromFormat('d-m-Y g:i A', $date);
        } else {
            $this->attributes['transfer_date'] = null;
        }
    }

    /**
     * @param $date
     */
    public function getPurchaseDateAttribute($value) {
        if(isset($value)) {
            return Carbon::parse($value)->format('d-m-Y g:i A');
        } else {
            return '';
        }
    }

    /**
     * @param $date
     */
    public function getSellingDateAttribute($value) {
        if(isset($value)) {
            return Carbon::parse($value)->format('d-m-Y g:i A');
        } else {
            return '';
        }
    }

    /**
     * @param $date
     */
    public function getTransferDateAttribute($value) {
        if(isset($value)) {
            return Carbon::parse($value)->format('d-m-Y g:i A');
        } else {
            return '';
        }
    }
}
