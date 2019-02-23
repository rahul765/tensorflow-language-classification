<?php namespace App\Http\Requests;

use App\Http\Requests\Request;

class UserRequest extends Request {

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
        $user = $this->route('users');
        $user_id = isset($user)? $user->id : NULL;
        $validation_rules = [
            'name'                  => 'required|min:6',
            'email'                 => 'required|email|unique:users,email,'.$user_id,
            'role'                 => 'required',
            'status'                => 'required',
        ];

        if(isset($user)) {
            $validation_rules['password'] = 'sometimes|min:4|confirmed';
            $validation_rules['password_confirmation'] = 'sometimes|min:4';
        } else {
            $validation_rules['password'] = 'required|min:4|confirmed';
            $validation_rules['password_confirmation'] = 'required|min:4';
        }
        return $validation_rules;
	}
}
