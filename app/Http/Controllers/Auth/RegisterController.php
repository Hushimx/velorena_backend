<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * The user has been registered.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function registered($request, $user)
    {
        return redirect($this->redirectTo)->with('status', __('Account created successfully! Welcome to Qaads.'));
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $rules = [
            'client_type' => 'required|in:individual,company',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'vat_number' => 'nullable|string|max:50',
            'cr_number' => 'nullable|string|max:50',
            'notes' => 'nullable|string|max:1000',
            'password' => 'required|string|min:8|confirmed',
        ];

        // Conditional validation based on client type
        if (isset($data['client_type']) && $data['client_type'] === 'individual') {
            $rules['full_name'] = 'required|string|max:255';
            $rules['company_name'] = 'nullable|string|max:255';
            $rules['contact_person'] = 'nullable|string|max:255';
        } else {
            $rules['company_name'] = 'required|string|max:255';
            $rules['contact_person'] = 'required|string|max:255';
            $rules['full_name'] = 'nullable|string|max:255';
        }

        return Validator::make($data, $rules);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        return User::create([
            'client_type' => $data['client_type'],
            'full_name' => $data['full_name'] ?? null,
            'company_name' => $data['company_name'] ?? null,
            'contact_person' => $data['contact_person'] ?? null,
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'address' => $data['address'] ?? null,
            'city' => $data['city'] ?? null,
            'vat_number' => $data['vat_number'] ?? null,
            'cr_number' => $data['cr_number'] ?? null,
            'notes' => $data['notes'] ?? null,
            'password' => Hash::make($data['password']),
        ]);
    }
}
