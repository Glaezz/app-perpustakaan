@extends('layouts.app')

@section('title', 'Profil')

@section('content')
<h1>Profil</h1>

<p><strong>Nama:</strong> {{ $user->name }}</p>
<p><strong>Email:</strong> {{ $user->email }}</p>
<p><strong>Peran:</strong> {{ ucfirst($user->role) }}</p>

<h2>Ganti Password</h2>

<form action="{{ route('profil.password.update') }}" method="POST">
    @csrf
    @method('PUT')

    <label for="current_password">Password lama</label>
    <input type="password" name="current_password" id="current_password" required>
    @error('current_password')
    <div class="error">{{ $message }}</div>
    @enderror

    <label for="password">Password baru</label>
    <input type="password" name="password" id="password" minlength="8" required>
    @error('password')
    <div class="error">{{ $message }}</div>
    @enderror

    <label for="password_confirmation">Konfirmasi password baru</label>
    <input type="password" name="password_confirmation" id="password_confirmation" minlength="8" required>

    <button type="submit" class="btn">Simpan Password</button>
</form>
@endsection