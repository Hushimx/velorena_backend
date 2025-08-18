<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class EditUser extends Component
{
    use WithFileUploads;

    public User $user;
    public $client_type = 'individual';
    public $full_name;
    public $company_name;
    public $contact_person;
    public $vat_number;
    public $cr_number;
    public $cr_document;
    public $vat_document;
    public $email;
    public $phone;
    public $address;
    public $city;
    public $country;
    public $notes;
    public $password;
    public $password_confirmation;

    public function mount(User $user)
    {
        $this->user = $user;
        $this->client_type = $user->client_type ?? 'individual';
        $this->full_name = $user->full_name;
        $this->company_name = $user->company_name;
        $this->contact_person = $user->contact_person;
        $this->vat_number = $user->vat_number;
        $this->cr_number = $user->cr_number;
        $this->email = $user->email;
        $this->phone = $user->phone;
        $this->address = $user->address;
        $this->city = $user->city;
        $this->country = $user->country;
        $this->notes = $user->notes;
    }

    protected function rules()
    {
        $rules = [
            'client_type' => 'required|in:individual,company',
            'email' => 'required|email|unique:users,email,' . $this->user->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
            'password' => ['nullable', 'confirmed', Password::defaults()],
        ];

        if ($this->client_type === 'individual') {
            $rules['full_name'] = 'required|string|max:255';
        } else {
            $rules['company_name'] = 'required|string|max:255';
            $rules['contact_person'] = 'required|string|max:255';
            $rules['vat_number'] = 'nullable|string|max:50';
            $rules['cr_number'] = 'nullable|string|max:50';
            $rules['cr_document'] = 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048';
            $rules['vat_document'] = 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048';
        }

        return $rules;
    }

    public function save()
    {
        $this->validate();

        $updateData = [
            'client_type' => $this->client_type,
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address,
            'city' => $this->city,
            'country' => $this->country,
            'notes' => $this->notes,
        ];

        if ($this->client_type === 'individual') {
            $updateData['full_name'] = $this->full_name;
            $updateData['company_name'] = null;
            $updateData['contact_person'] = null;
            $updateData['vat_number'] = null;
            $updateData['cr_number'] = null;
        } else {
            $updateData['full_name'] = null;
            $updateData['company_name'] = $this->company_name;
            $updateData['contact_person'] = $this->contact_person;
            $updateData['vat_number'] = $this->vat_number;
            $updateData['cr_number'] = $this->cr_number;

            // Handle file uploads
            if ($this->cr_document) {
                $updateData['cr_document'] = $this->cr_document->store('documents', 'public');
            }
            if ($this->vat_document) {
                $updateData['vat_document'] = $this->vat_document->store('documents', 'public');
            }
        }

        if ($this->password) {
            $updateData['password'] = Hash::make($this->password);
        }

        $this->user->update($updateData);

        session()->flash('message', trans('users.user_updated_successfully'));
    }

    public function render()
    {
        return view('livewire.edit-user');
    }
}
