<x-backoffice.layout.main>
    <x-backoffice.partials.breadcrumb title="Partner" pretitle="Tambah Partner" />
    <div class="col-12">
        <div class="card">
            <form action="{{ route('partners.store') }}" method="POST" class="card" enctype="multipart/form-data">
                @csrf
                <div class="card-header">
                    <h4 class="card-title">Form Tambah Partner</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="row">
                                <div class="col-md-6 col-xl-12">
                                    <div class="mb-3">
                                        <label class="form-label">Nama Partner</label>
                                        <input type="text" class="form-control" id="name" name="name"
                                            value="{{ old('name') }}" placeholder="Nama Partner" required>
                                        @error('name')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Gambar Partner</label>
                                        <input type="file" class="form-control" name="file" accept="image/*" required>
                                        <small class="form-hint">Format: JPG, PNG, GIF. Maksimal ukuran file 2MB</small>
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
                        <a href="{{ route('partners.index') }}" class="btn btn-link">Batal</a>
                        <button type="submit" class="btn btn-primary ms-auto">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-backoffice.layout.main>

