@extends('layouts.app')
@section('content')
<h2>Daftar User</h2>
    <div class="d-flex justify-content-between align-items-end mb-3">
    <div>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">Tambah User</button>
    </div>
        <form class="d-inline-flex" method="GET" action="{{ route('user.index') }}">
            <input class="form-control me-2" type="search" name="search" placeholder="Cari User" value="{{ request('search') }}">
            <button class="btn btn-outline-primary me-2" type="submit">Cari</button>
        </form>
</div>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nama</th>
                <th>Email</th>
                <th>Hobby</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        <ul class="mb-0">
                            @foreach ($user->hobbies as $hobi)
                                <li>{{ $hobi->name }}</li>
                            @endforeach
                        </ul>
                    </td>
                    <td>
                        <a href="{{ route('user.edit', $user->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('user.destroy', $user->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm" onclick="return confirm('Yakin?')">Hapus</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{ $users->withQueryString()->links() }}

    <!-- Modal Tambah -->
    <div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form class="modal-content" method="POST" action="{{ route('user.store') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="createModalLabel">Tambah User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Nama</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Hobby</label>
                        <input type="text" name="hobbies[]" class="form-control mb-2" placeholder="Hobby 1">
                        <input type="text" name="hobbies[]" class="form-control mb-2" placeholder="Hobby 2">
                        <input type="text" name="hobbies[]" class="form-control mb-2" placeholder="Hobby 3">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
@endsection
