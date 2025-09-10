@extends('admin.layouts.app')

@section('title', __('admin.bulk_upload_leads'))

@section('content')
<div class="space-y-6">
    <div class="card">
        <div class="card-header">
            <h3 class="text-xl font-semibold" style="color: var(--brand-brown);">{{ __('admin.bulk_upload_leads') }}</h3>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.leads.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> {{ __('admin.back') }}
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2">
                    <form action="{{ route('admin.leads.bulk-upload.process') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="space-y-4">
                            <div>
                                <label for="file" class="form-label">{{ __('admin.select_excel_file') }}</label>
                                <input type="file" class="form-control @error('file') border-red-500 @enderror" 
                                       id="file" name="file" accept=".xlsx,.xls,.csv" required>
                                @error('file')
                                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                @enderror
                                <small class="text-gray-500 text-sm">
                                    {{ __('admin.supported_files') }}: Excel (.xlsx, .xls) {{ __('admin.or') }} CSV. {{ __('admin.max_size') }}: 10MB
                                </small>
                            </div>
                            
                            <div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-upload"></i> {{ __('admin.upload_file') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="lg:col-span-1">
                    <div class="card" style="background: var(--brand-yellow-light);">
                        <div class="card-header">
                            <h5 class="font-semibold" style="color: var(--brand-brown);">{{ __('admin.upload_instructions') }}</h5>
                        </div>
                        <div class="card-body">
                            <ol class="space-y-2 text-sm">
                                <li>{{ __('admin.download_excel_template') }}</li>
                                <li>{{ __('admin.fill_data_template') }}</li>
                                <li>{{ __('admin.save_excel_format') }}</li>
                                <li>{{ __('admin.upload_file_here') }}</li>
                            </ol>
                            
                            <div class="mt-4">
                                <a href="{{ route('admin.leads.download-template') }}" 
                                   class="btn btn-success">
                                    <i class="fas fa-download"></i> {{ __('admin.download_template') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="mt-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="font-semibold" style="color: var(--brand-brown);">{{ __('admin.required_fields_info') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="overflow-x-auto">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>{{ __('admin.field_name') }}</th>
                                        <th>{{ __('admin.required') }}</th>
                                        <th>{{ __('admin.description') }}</th>
                                        <th>{{ __('admin.allowed_values') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{ __('admin.company_name') }}</td>
                                        <td><span class="badge badge-danger">{{ __('admin.required') }}</span></td>
                                        <td>{{ __('admin.company_name_desc') }}</td>
                                        <td>{{ __('admin.text') }}</td>
                                    </tr>
                                    <tr>
                                        <td>{{ __('admin.contact_person') }}</td>
                                        <td><span class="badge badge-danger">{{ __('admin.required') }}</span></td>
                                        <td>{{ __('admin.contact_person_desc') }}</td>
                                        <td>{{ __('admin.text') }}</td>
                                    </tr>
                                    <tr>
                                        <td>{{ __('admin.email') }}</td>
                                        <td><span class="badge badge-danger">{{ __('admin.required') }}</span></td>
                                        <td>{{ __('admin.email_desc') }}</td>
                                        <td>{{ __('admin.valid_email') }}</td>
                                    </tr>
                                    <tr>
                                        <td>{{ __('admin.phone') }}</td>
                                        <td><span class="badge badge-inactive">{{ __('admin.optional') }}</span></td>
                                        <td>{{ __('admin.phone_desc') }}</td>
                                        <td>{{ __('admin.text') }}</td>
                                    </tr>
                                    <tr>
                                        <td>{{ __('admin.address') }}</td>
                                        <td><span class="badge badge-inactive">{{ __('admin.optional') }}</span></td>
                                        <td>{{ __('admin.address_desc') }}</td>
                                        <td>{{ __('admin.text') }}</td>
                                    </tr>
                                    <tr>
                                        <td>{{ __('admin.notes') }}</td>
                                        <td><span class="badge badge-inactive">{{ __('admin.optional') }}</span></td>
                                        <td>{{ __('admin.notes_desc') }}</td>
                                        <td>{{ __('admin.text') }}</td>
                                    </tr>
                                    <tr>
                                        <td>{{ __('admin.status') }}</td>
                                        <td><span class="badge badge-inactive">{{ __('admin.optional') }}</span></td>
                                        <td>{{ __('admin.lead_status_desc') }}</td>
                                        <td>{{ __('admin.lead_status_values') }}</td>
                                    </tr>
                                    <tr>
                                        <td>{{ __('admin.priority') }}</td>
                                        <td><span class="badge badge-inactive">{{ __('admin.optional') }}</span></td>
                                        <td>{{ __('admin.priority_desc') }}</td>
                                        <td>{{ __('admin.priority_values') }}</td>
                                    </tr>
                                    <tr>
                                        <td>{{ __('admin.category') }}</td>
                                        <td><span class="badge badge-inactive">{{ __('admin.optional') }}</span></td>
                                        <td>{{ __('admin.category_desc') }}</td>
                                        <td>{{ __('admin.category_from_system') }}</td>
                                    </tr>
                                    <tr>
                                        <td>{{ __('admin.marketer_email') }}</td>
                                        <td><span class="badge badge-inactive">{{ __('admin.optional') }}</span></td>
                                        <td>{{ __('admin.marketer_email_desc') }}</td>
                                        <td>{{ __('admin.valid_email') }}</td>
                                    </tr>
                                    <tr>
                                        <td>{{ __('admin.next_follow_up') }}</td>
                                        <td><span class="badge badge-inactive">{{ __('admin.optional') }}</span></td>
                                        <td>{{ __('admin.next_follow_up_desc') }}</td>
                                        <td>{{ __('admin.date_format') }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
