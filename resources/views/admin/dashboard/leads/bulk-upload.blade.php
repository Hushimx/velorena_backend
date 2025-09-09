@extends('admin.layouts.app')

@section('title', 'رفع الـ Leads بالجملة')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">رفع الـ Leads بالجملة</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.leads.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> العودة
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <form action="{{ route('admin.leads.bulk-upload.process') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <label for="file">اختر ملف Excel</label>
                                    <input type="file" class="form-control @error('file') is-invalid @enderror" 
                                           id="file" name="file" accept=".xlsx,.xls,.csv" required>
                                    @error('file')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">
                                        الملفات المدعومة: Excel (.xlsx, .xls) أو CSV. الحد الأقصى: 10MB
                                    </small>
                                </div>
                                
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-upload"></i> رفع الملف
                                    </button>
                                </div>
                            </form>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-light">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">تعليمات الرفع</h5>
                                </div>
                                <div class="card-body">
                                    <ol>
                                        <li>قم بتحميل قالب Excel أولاً</li>
                                        <li>املأ البيانات حسب القالب</li>
                                        <li>احفظ الملف بصيغة Excel</li>
                                        <li>ارفع الملف هنا</li>
                                    </ol>
                                    
                                    <div class="mt-3">
                                        <a href="{{ route('admin.leads.download-template') }}" 
                                           class="btn btn-success btn-sm">
                                            <i class="fas fa-download"></i> تحميل القالب
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">معلومات الحقول المطلوبة</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>اسم الحقل</th>
                                                    <th>مطلوب</th>
                                                    <th>الوصف</th>
                                                    <th>القيم المسموحة</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>Company Name</td>
                                                    <td><span class="badge badge-danger">مطلوب</span></td>
                                                    <td>اسم الشركة</td>
                                                    <td>نص</td>
                                                </tr>
                                                <tr>
                                                    <td>Contact Person</td>
                                                    <td><span class="badge badge-danger">مطلوب</span></td>
                                                    <td>اسم الشخص المسؤول</td>
                                                    <td>نص</td>
                                                </tr>
                                                <tr>
                                                    <td>Email</td>
                                                    <td><span class="badge badge-danger">مطلوب</span></td>
                                                    <td>البريد الإلكتروني</td>
                                                    <td>بريد إلكتروني صحيح</td>
                                                </tr>
                                                <tr>
                                                    <td>Phone</td>
                                                    <td><span class="badge badge-secondary">اختياري</span></td>
                                                    <td>رقم الهاتف</td>
                                                    <td>نص</td>
                                                </tr>
                                                <tr>
                                                    <td>Address</td>
                                                    <td><span class="badge badge-secondary">اختياري</span></td>
                                                    <td>العنوان</td>
                                                    <td>نص</td>
                                                </tr>
                                                <tr>
                                                    <td>Notes</td>
                                                    <td><span class="badge badge-secondary">اختياري</span></td>
                                                    <td>ملاحظات</td>
                                                    <td>نص</td>
                                                </tr>
                                                <tr>
                                                    <td>Status</td>
                                                    <td><span class="badge badge-secondary">اختياري</span></td>
                                                    <td>حالة الـ Lead</td>
                                                    <td>new, contacted, qualified, proposal_sent, negotiation, closed_won, closed_lost</td>
                                                </tr>
                                                <tr>
                                                    <td>Priority</td>
                                                    <td><span class="badge badge-secondary">اختياري</span></td>
                                                    <td>الأولوية</td>
                                                    <td>low, medium, high</td>
                                                </tr>
                                                <tr>
                                                    <td>Category</td>
                                                    <td><span class="badge badge-secondary">اختياري</span></td>
                                                    <td>الفئة</td>
                                                    <td>اسم الفئة من النظام</td>
                                                </tr>
                                                <tr>
                                                    <td>Marketer Email</td>
                                                    <td><span class="badge badge-secondary">اختياري</span></td>
                                                    <td>بريد المسوق</td>
                                                    <td>بريد إلكتروني صحيح</td>
                                                </tr>
                                                <tr>
                                                    <td>Next Follow Up</td>
                                                    <td><span class="badge badge-secondary">اختياري</span></td>
                                                    <td>تاريخ المتابعة التالية</td>
                                                    <td>تاريخ (YYYY-MM-DD)</td>
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
        </div>
    </div>
</div>
@endsection
