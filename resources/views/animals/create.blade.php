@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="card shadow rounded-4 p-4">
        <h2 class="mb-4 text-center">🐑 Add New Animal</h2>

        {{-- عرض الأخطاء --}}
        @if ($errors->any())
        <div class="alert alert-danger rounded-3">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                <li>⚠️ {{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('animals.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label class="form-label fw-bold">🐮 Type</label>
                <input type="text" name="type" class="form-control" placeholder="مثلاً: غنم، بقر..." required>
            </div>

            <div class="mb-3">
                <label for="health_status" class="form-label">🩺 Health Status</label>
                <select name="health_status" id="health_status" class="form-select" required>
                    <option value="healthy">Healthy</option>
                    <option value="sick">Sick</option>
                    <option value="under_treatment">Under Treatment</option>
                </select>
            </div>


            <div class="mb-3">
                <label class="form-label fw-bold">⚤ Gender</label>
                <select name="gender" class="form-control" required>
                    <option value="">--Select gender--</option>
                    <option value="male">male</option>
                    <option value="female">female</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">📅 Birth Date</label>
                <input type="date" name="birth_date" class="form-control">
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">📝 Notes</label>
                <textarea name="notes" class="form-control" rows="3" placeholder="أي ملاحظات إضافية؟"></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">🖼 Upload Images</label>
                <input type="file" name="images[]" class="form-control" multiple accept="image/*">
                <small class="text-muted">يمكنك رفع أكثر من صورة</small>
            </div>

            <div class="d-flex justify-content-between">
                <a href="{{ route('animals.index') }}" class="btn btn-secondary">⬅️ رجوع</a>
                <button type="submit" class="btn btn-success">💾 Save Animal</button>
            </div>
        </form>
    </div>
</div>
@endsection