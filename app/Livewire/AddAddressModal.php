<?php

namespace App\Livewire;

use App\Models\Address;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class AddAddressModal extends Component
{
    public $showModal = false;
    public $name = '';
    public $contact_name = '';
    public $contact_phone = '';
    public $address_line = '';
    public $city = '';
    public $district = '';
    public $postal_code = '';
    public $country = 'Saudi Arabia';
    public $latitude = null;
    public $longitude = null;
    public $delivery_instruction = 'hand_to_me';
    public $is_default = false;

    protected $rules = [
        'name' => 'required|string|max:255',
        'contact_name' => 'required|string|max:255',
        'contact_phone' => 'required|string|max:20',
        'address_line' => 'required|string|max:500',
        'city' => 'required|string|max:100',
        'district' => 'required|string|max:100',
        'postal_code' => 'nullable|string|max:20',
        'country' => 'required|string|max:100',
        'delivery_instruction' => 'required|in:hand_to_me,leave_at_spot',
        'is_default' => 'boolean'
    ];

    protected $messages = [
        'name.required' => 'اسم العنوان مطلوب',
        'contact_name.required' => 'اسم جهة الاتصال مطلوب',
        'contact_phone.required' => 'رقم الهاتف مطلوب',
        'address_line.required' => 'عنوان الشارع مطلوب',
        'city.required' => 'المدينة مطلوبة',
        'district.required' => 'الحي مطلوب',
    ];

    public function openModal()
    {
        $this->showModal = true;
        $this->resetForm();

        // Prevent body scroll
        $this->js('document.body.style.overflow = "hidden";');
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();

        // Allow body scroll
        $this->js('document.body.style.overflow = "";');
    }

    public function resetForm()
    {
        $this->name = '';
        $this->contact_name = '';
        $this->contact_phone = '';
        $this->address_line = '';
        $this->city = '';
        $this->district = '';
        $this->postal_code = '';
        $this->country = 'Saudi Arabia';
        $this->latitude = null;
        $this->longitude = null;
        $this->delivery_instruction = 'hand_to_me';
        $this->is_default = false;
        $this->resetErrorBag();
    }

    public function saveAddress()
    {
        $this->validate();

        try {
            $user = Auth::user();

            // If this is set as default, unset other defaults
            if ($this->is_default) {
                Address::where('user_id', $user->id)
                    ->where('is_default', true)
                    ->update(['is_default' => false]);
            }

            $address = Address::create([
                'user_id' => $user->id,
                'name' => $this->name,
                'contact_name' => $this->contact_name,
                'contact_phone' => $this->contact_phone,
                'address_line' => $this->address_line,
                'city' => $this->city,
                'district' => $this->district,
                'postal_code' => $this->postal_code,
                'country' => $this->country,
                'latitude' => $this->latitude,
                'longitude' => $this->longitude,
                'delivery_instruction' => $this->delivery_instruction,
                'is_default' => $this->is_default,
            ]);

            // Dispatch event to refresh parent component
            $this->dispatch('addressAdded', $address->id);

            session()->flash('success', 'تم إضافة العنوان بنجاح');
            $this->closeModal();

            // Refresh the page to show the new address
            $this->redirect(request()->header('Referer'));

        } catch (\Exception $e) {
            session()->flash('error', 'حدث خطأ أثناء إضافة العنوان');
            Log::error('Failed to create address', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id()
            ]);
        }
    }

    public function render()
    {
        return view('livewire.add-address-modal');
    }
}
