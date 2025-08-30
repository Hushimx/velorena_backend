<?php

namespace App\Livewire;

use App\Models\Appointment;
use App\Models\Designer;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class DesignerAppointmentsWithOrders extends Component
{
    use WithPagination;

    public $status_filter = '';
    public $date_filter = '';
    public $designer;

    protected $queryString = [
        'status_filter' => ['except' => ''],
        'date_filter' => ['except' => ''],
    ];

    public function mount()
    {
        $this->designer = Auth::guard('designer')->user();
        if (!$this->designer) {
            abort(403, 'Designer access required');
        }
    }

    public function clearFilters()
    {
        $this->reset(['status_filter', 'date_filter']);
        $this->resetPage();
    }

    public function acceptAppointment($appointmentId)
    {
        $appointment = Appointment::where('designer_id', $this->designer->id)
            ->findOrFail($appointmentId);

        $appointment->accept();

        session()->flash('success', 'Appointment accepted successfully.');
    }

    public function rejectAppointment($appointmentId)
    {
        $appointment = Appointment::where('designer_id', $this->designer->id)
            ->findOrFail($appointmentId);

        $appointment->reject();

        session()->flash('success', 'Appointment rejected.');
    }

    public function completeAppointment($appointmentId)
    {
        $appointment = Appointment::where('designer_id', $this->designer->id)
            ->findOrFail($appointmentId);

        $appointment->complete();

        session()->flash('success', 'Appointment marked as completed.');
    }

    public function viewAppointmentDetails($appointmentId)
    {
        return redirect()->route('designer.appointments.show', $appointmentId);
    }

    public function render()
    {
        $appointments = Appointment::where('designer_id', $this->designer->id)
            ->with([
                'user:id,name,email,phone',
                'orders.items.product',
                'orders.items.product.options.values'
            ])
            ->when($this->status_filter, function ($query) {
                return $query->where('status', $this->status_filter);
            })
            ->when($this->date_filter, function ($query) {
                return $query->where('appointment_date', $this->date_filter);
            })
            ->orderBy('appointment_date')
            ->orderBy('appointment_time')
            ->paginate(10);

        return view('livewire.designer-appointments-with-orders', [
            'appointments' => $appointments
        ]);
    }
}
