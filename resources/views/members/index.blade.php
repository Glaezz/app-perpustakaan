@extends('layouts.app')

@section('title', 'Daftar Anggota')

@section('content')
<h1>Daftar Anggota</h1>
<p><a href="{{ route('members.create') }}" class="btn">+ Tambah Anggota</a></p>

<form action="{{ route('members.index') }}" method="GET">
    <input style="width: 89%;" type="text" name="search" id="search" value="{{ request()->search }}" placeholder="Cari anggota...">
    <button style="width: 10%;" type="submit" class="btn">Cari</button>
</form>
@error('search')
<div class="error">{{ $message }}</div>
@enderror

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Nama</th>
            <th>NIM</th>
            <th>Email</th>
            <th>No. Telepon</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($members as $member)
        <tr>
            <td>{{ $member['id'] }}</td>
            <td>{{ $member['nama'] }}</td>
            <td>{{ $member['nim'] }}</td>
            <td>{{ $member['email'] }}</td>
            <td>{{ $member['nomor_telepon'] }}</td>
            <td>{{ ucfirst($member['status']) }}</td>
            <td>
                <a href="{{ route('members.show', $member['id']) }}">Detail</a>
                |
                <a href="{{ route('members.edit', $member['id']) }}">Edit</a>
                |
                <form class="inline" action="{{ route('members.destroy', $member['id']) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit">Hapus</button>
                </form>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="6">Belum ada data anggota.</td>
        </tr>
        @endforelse
    </tbody>
</table>

<p><em>{{ $members->appends(request()->query())->links() }}</em></p>
@endsection