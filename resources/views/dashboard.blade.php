@extends('layout')

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Kategori</h5>
                <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal">+</button>
            </div>
            <ul class="list-group list-group-flush">
                @forelse($categories as $cat)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    {{ $cat->name }}
                    <form action="{{ route('categories.destroy', $cat->id) }}" method="POST" onsubmit="return confirm('Hapus kategori ini? Semua tugas di dalamnya juga akan terhapus.')">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm text-danger"><i class="fas fa-trash"></i></button>
                    </form>
                </li>
                @empty
                <li class="list-group-item text-muted">Belum ada kategori</li>
                @endforelse
            </ul>
        </div>

        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">Riwayat Aktivitas</h5>
            </div>
            <div class="card-body p-0" style="max-height: 300px; overflow-y: auto;">
                <ul class="list-group list-group-flush">
                    @foreach($histories as $history)
                    <li class="list-group-item small">
                        <strong>{{ $history->action }}</strong>: {{ $history->description }}
                        <br>
                        <span class="text-muted" style="font-size: 0.8em;">{{ $history->created_at->diffForHumans() }}</span>
                        <form action="{{ route('histories.destroy', $history->id) }}" method="POST" class="d-inline float-end">
                            @csrf @method('DELETE')
                            <button class="btn btn-link text-danger p-0" style="font-size: 0.8em;">Hapus</button>
                        </form>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                <h4 class="mb-0">Daftar Tugas</h4>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTodoModal">
                    <i class="fas fa-plus"></i> Tambah Tugas
                </button>
            </div>
            <div class="card-body">
                @if($categories->count() == 0)
                    <div class="alert alert-warning">Buat kategori dulu sebelum menambah tugas!</div>
                @endif

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Status</th>
                                <th>Tugas</th>
                                <th>Prioritas</th>
                                <th>Due Date</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($todos as $todo)
                            <tr class="{{ $todo->is_completed ? 'table-light' : '' }}">
                                <td>
                                    <form action="{{ route('todos.toggle', $todo->id) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <button class="btn btn-sm {{ $todo->is_completed ? 'btn-success' : 'btn-outline-secondary' }}">
                                            <i class="fas {{ $todo->is_completed ? 'fa-check' : 'fa-circle' }}"></i>
                                        </button>
                                    </form>
                                </td>
                                <td>
                                    <span class="{{ $todo->is_completed ? 'completed' : '' }} fw-bold">{{ $todo->title }}</span>
                                    <br>
                                    <small class="text-muted">{{ $todo->category->name }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $todo->priority->color }}">
                                        {{ $todo->priority->name }}
                                    </span>
                                </td>
                                <td>
                                    <small>{{ $todo->due_date ? $todo->due_date->format('d M Y') : '-' }}</small>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-info text-white" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#editTodoModal{{ $todo->id }}">
                                        <i class="fas fa-edit"></i>
                                    </button>

                                    <form action="{{ route('todos.destroy', $todo->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>

                            <div class="modal fade" id="editTodoModal{{ $todo->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <form action="{{ route('todos.update', $todo->id) }}" method="POST">
                                        @csrf @method('PUT')
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Edit Tugas</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label>Judul</label>
                                                    <input type="text" name="title" class="form-control" value="{{ $todo->title }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label>Deskripsi</label>
                                                    <textarea name="description" class="form-control">{{ $todo->description }}</textarea>
                                                </div>
                                                <div class="row">
                                                    <div class="col">
                                                        <label>Kategori</label>
                                                        <select name="category_id" class="form-select">
                                                            @foreach($categories as $cat)
                                                                <option value="{{ $cat->id }}" {{ $todo->category_id == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col">
                                                        <label>Prioritas</label>
                                                        <select name="priority_id" class="form-select">
                                                            @foreach($priorities as $prio)
                                                                <option value="{{ $prio->id }}" {{ $todo->priority_id == $prio->id ? 'selected' : '' }}>{{ $prio->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="mt-3">
                                                    <label>Tenggat Waktu</label>
                                                    <input type="date" name="due_date" class="form-control" value="{{ $todo->due_date ? $todo->due_date->format('Y-m-d') : '' }}">
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            @empty
                            <tr><td colspan="5" class="text-center py-4">Tidak ada tugas. Semangat!</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addCategoryModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('categories.store') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Kategori Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="text" name="name" class="form-control" placeholder="Nama Kategori (mis: Kerja, Rumah)" required>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="addTodoModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('todos.store') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Tugas Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Judul Tugas</label>
                        <input type="text" name="title" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Deskripsi (Opsional)</label>
                        <textarea name="description" class="form-control"></textarea>
                    </div>
                    <div class="row mb-3">
                        <div class="col">
                            <label>Kategori</label>
                            <select name="category_id" class="form-select" required>
                                <option value="">Pilih...</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col">
                            <label>Prioritas</label>
                            <select name="priority_id" class="form-select" required>
                                @foreach($priorities as $prio)
                                    <option value="{{ $prio->id }}">{{ $prio->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label>Tenggat Waktu</label>
                        <input type="date" name="due_date" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Simpan Tugas</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection