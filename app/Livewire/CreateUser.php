<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class CreateUser extends Component
{
    public $name;
    public $email;
    public $password;
    public $password_confirmation;

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => ['required', 'confirmed', Password::defaults()],
        ];
    }


    public function save()
    {
        $this->validate();

        User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
        ]);

        // Clear the form
        $this->reset(['name', 'email', 'password', 'password_confirmation']);

        // Show success message
        session()->flash('message', 'User created successfully!');

        // Optionally redirect
        // return redirect()->to('/users');
    }

    public function render()
    {
        return view('livewire.create-user');
    }
}
