@extends('layouts.app')

@section('content')
<h2>Edit User</h2>
<form method="POST" action="{{ route('user.update', $user->id) }}">
    @csrf
    @method('PUT')
    <div class="mb-3">
        <label>Nama</label>
        <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
    </div>
    <div class="mb-3">
        <label>Email</label>
        <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
    </div>
    <div class="mb-3">
        <label>Password (kosongkan jika tidak ingin diubah)</label>
        <input type="password" name="password" class="form-control">
    </div>
    <div class="mb-3">
        <label>Hobi</label>
        @foreach ($user->hobbies as $i => $hobby)
            <input type="text" name="hobbies[]" class="form-control mb-2" value="{{ $hobby->name }}">
        @endforeach
        @for ($i = count($user->hobbies); $i < 3; $i++)
            <input type="text" name="hobbies[]" class="form-control mb-2" placeholder="Hobi tambahan">
        @endfor
    </div>
    <button type="submit" class="btn btn-primary">Update</button>
    <a href="{{ route('user.index') }}" class="btn btn-secondary">Batal</a>
</form>
@endsection
