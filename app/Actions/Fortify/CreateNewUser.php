<?php

namespace App\Actions\Fortify;

use App\Models\Client;
use App\Models\User;
use App\Models\Workshop;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => $this->passwordRules(),
            'type' => ['required', 'string'],
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        ])->validate();

        $user = User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
            'type' => $input['type'],
        ]);

        if($input['type'] == "taller"){
            Workshop::create([
                'user_id' => $user->id
            ]);
        }
        if($input['type'] == "cliente"){
            Client::create([
                'user_id' => $user->id
            ]);
        }

        return $user;
    }
}
