<x-backoffice.layout.main>
    <x-backoffice.partials.breadcrumb title="Lisensi" pretitle="Edit Lisensi" />
    <div class="col-12">
        <div class="card">
            <form action="{{ route('licenses.update', $license) }}" method="POST" class="card"
                enctype="multipart/form-data">
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
                                            value="{{ old('name', $license->name) }}" placeholder="Nama Lisensi"
                                            required>
                                        @error('name')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Kode Lisensi</label>
                                        <input type="text" class="form-control" id="code" name="code"
                                            value="{{ old('code', $license->code) }}" placeholder="Kode Lisensi"
                                            required>
                                        @error('code')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Tipe Lisensi</label>
                                        <input type="text" class="form-control" id="type" name="type"
                                            value="{{ old('type', $license->type) }}" placeholder="Tipe Lisensi"
                                            required>
                                        @error('type')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">File Lisensi (Gambar/PDF)</label>
                                        @if ($license->file)
                                            <div class="mb-2">
                                                @php
                                                    $ext = pathinfo($license->file, PATHINFO_EXTENSION);
                                                @endphp
                                                @if (in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                                                    <img src="{{ asset('images/licenses/' . $license->file) }}"
                                                        alt="{{ $license->name }}" class="img-thumbnail"
                                                        style="max-width: 200px;">
                                                @else
                                                    <a href="{{ asset('images/licenses/' . $license->file) }}"
                                                        target="_blank" class="btn btn-sm btn-outline-secondary">
                                                        Lihat File Saat Ini ({{ strtoupper($ext) }})
                                                    </a>
                                                @endif
                                            </div>
                                        @endif
                                        <input type="file" class="form-control" name="file"
                                            accept="image/png, image/jpeg, application/pdf">
                                        <small class="form-hint">Format yang didukung: JPG, PNG, PDF. Kosongkan jika
                                            tidak ingin mengubah file.</small>
                                        @error('file')
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
