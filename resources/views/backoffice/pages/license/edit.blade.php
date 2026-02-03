<x-backoffice.layout.main>
    <x-backoffice.partials.breadcrumb title="Lisensi" pretitle="Edit Lisensi" />
    <div class="col-12">
        <div class="card">
            <form action="{{ route('licenses.update', $license) }}" method="POST" class="card">
                @csrf
                @method('PUT')
                <div class="card-header">
                    <h4 class="card-title">Form Edit Lisensi</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="row">
                                <div class="col-md-6 col-xl-12">
                                    <div class="mb-3">
                                        <label class="form-label">Nama Lisensi</label>
                                        <input type="text" class="form-control" id="name" name="name"
                                            value="{{ old('name', $license->name) }}" placeholder="Nama Lisensi" required>
                                        @error('name')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Kode Lisensi</label>
                                        <input type="text" class="form-control" id="code" name="code"
                                            value="{{ old('code', $license->code) }}" placeholder="Kode Lisensi" required>
                                        @error('code')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Tipe Lisensi</label>
                                        <input type="text" class="form-control" id="type" name="type"
                                            value="{{ old('type', $license->type) }}" placeholder="Tipe Lisensi" required>
                                        @error('type')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-end">
                    <div class="d-flex">
                        <a href="{{ route('licenses.index') }}" class="btn btn-link">Batal</a>
                        <button type="submit" class="btn btn-primary ms-auto">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-backoffice.layout.main>

