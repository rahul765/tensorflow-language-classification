<?php namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Support\Facades\Auth;

class UserProfileRequest extends Request {

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
        $user = Auth::user();
        $user_id = isset($user)? $user->id : NULL;
        $is_dealer = ($user->role == 'DEALER');

        $validation_rules = [
            'name'                  => 'required|min:6',
            'password'              => 'sometimes|min:4|confirmed',
        ];
        if($is_dealer) {
            $validation_rules['contact_person'] = 'required';
            $validation_rules['state']          = 'required';
            $validation_rules['city']           = 'required';
            $validation_rules['location']       = 'required';
            $validation_rules['address']        = 'required';
        }
        return $validation_rules;
	}
}
