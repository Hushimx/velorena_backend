<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class CreateUser extends Component
{
    use WithFileUploads;

    public $client_type = 'individual';
    public $full_name;
    public $company_name;
    public $contact_person;
    public $email;
    public $phone;
    public $address;
    public $city;
    public $country;
    public $vat_number;
    public $cr_number;
    public $cr_document;
    public $vat_document;
    public $notes;
    public $password;
    public $password_confirmation;

    protected function rules()
    {
        $rules = [
            'client_type' => 'required|in:individual,company',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
            'password' => ['required', 'confirmed', Password::defaults()],
        ];

        if ($this->client_type === 'individual') {
            $rules['full_name'] = 'required|string|max:255';
        } else {
            $rules['company_name'] = 'required|string|max:255';
            $rules['contact_person'] = 'required|string|max:255';
            $rules['vat_number'] = 'nullable|string|max:50';
            $rules['cr_number'] = 'nullable|string|max:50';
            $rules['cr_document'] = 'nullable|file|mimes:pdf,jpg,png|max:2048';
            $rules['vat_document'] = 'nullable|file|mimes:pdf,jpg,png|max:2048';
        }

        return $rules;
    }

    public function save()
    {
        $validated = $this->validate();

        $userData = [
            'client_type' => $this->client_type,
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address,
            'city' => $this->city,
            'country' => $this->country,
            'notes' => $this->notes,
            'password' => Hash::make($this->password),
        ];

        if ($this->client_type === 'individual') {
            $userData['full_name'] = $this->full_name;
        } else {
            $userData['company_name'] = $this->company_name;
            $userData['contact_person'] = $this->contact_person;
            $userData['vat_number'] = $this->vat_number;
            $userData['cr_number'] = $this->cr_number;

            if ($this->cr_document) {
                $userData['cr_document_path'] = $this->cr_document->store('company-documents');
            }

            if ($this->vat_document) {
                $userData['vat_document_path'] = $this->vat_document->store('company-documents');
            }
        }

        User::create($userData);

        session()->flash('message', trans('users.user_created_successfully'));
        $this->reset();
    }

    public function render()
    {
        return view('livewire.create-user');
    }
}
