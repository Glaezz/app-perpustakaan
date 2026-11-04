@extends('layouts.app')

@section('title', 'Daftar Member')

@section('content')
<h1>Tambah Member</h1>
<p><a href="{{ route('members.index') }}">&larr; Kembali ke daftar member</a></p>

<form action="{{ route('members.store') }}" method="POST">
    @csrf

    <label for="nama">Nama</label>
    <input type="text" name="nama" id="nama" value="{{ old('nama') }}">
    @error('nama')
    <div class="error">{{ $message }}</div>
    @enderror

    <label for="nim">NIM</label>
    <input type="number" name="nim" id="nim" value="{{ old('nim') }}">
    @error('nim')
    <div class="error">{{ $message }}</div>
    @enderror

    <label for="email">Email</label>
    <input type="text" name="email" id="email" value="{{ old('email') }}">
    @error('email')
    <div class="error">{{ $message }}</div>
    @enderror

    <label for="nomor_telepon">Nomor Telepon</label>
    <input type="text" name="nomor_telepon" id="nomor_telepon" value="{{ old('nomor_telepon') }}">
    @error('nomor_telepon')
    <div class="error">{{ $message }}</div>
    @enderror

    <label for="alamat">Alamat</label>
    <textarea name="alamat" id="alamat" rows="4">{{ old('alamat') }}</textarea>
    @error('alamat')
    <div class="error">{{ $message }}</div>
    @enderror

    <label for="status">status</label>
    <select name="status" id="status">
        <option value="aktif" {{ old('status') === 'aktif' ? 'selected' : '' }}>Aktif</option>
        <option value="nonaktif" {{ old('status') === 'nonaktif' ? 'selected' : '' }}>Tidak Aktif</option>
    </select>
    @error('status')
    <div class="error">{{ $message }}</div>
    @enderror

    <button type="submit" class="btn">Simpan</button>
</form>

@endsection