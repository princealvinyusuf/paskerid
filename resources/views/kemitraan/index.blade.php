@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Daftar Pendaftaran Kemitraan</h2>
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <a href="{{ route('kemitraan.create') }}" class="btn btn-primary mb-3">Tambah Pendaftaran</a>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>No</th>
                <th>PIC</th>
                <th>Instansi</th>
                <th>Kategori/Sektor</th>
                <th>Jenis Kemitraan</th>
                <th>Tanggal Daftar</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($kemitraan as $item)
                <tr>
                    <td>{{ $loop->iteration + ($kemitraan->currentPage() - 1) * $kemitraan->perPage() }}</td>
                    <td>{{ $item->pic_name }}</td>
                    <td>{{ $item->institution_name }}</td>
                    <td>{{ $item->sector_category }}</td>
                    <td>{{ $item->partnership_type }}</td>
                    <td>{{ $item->created_at->format('d-m-Y') }}</td>
                    <td>
                        <a href="{{ route('kemitraan.show', $item->id) }}" class="btn btn-info btn-sm">Detail</a>
                        <a href="{{ route('kemitraan.edit', $item->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('kemitraan.destroy', $item->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?')">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">Belum ada pendaftaran kemitraan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    {{ $kemitraan->links() }}
</div>
@endsection 