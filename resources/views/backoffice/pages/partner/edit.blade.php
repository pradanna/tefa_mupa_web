<x-backoffice.layout.main>
    <x-backoffice.partials.breadcrumb title="Partner" pretitle="Edit Partner" />
    <div class="col-12">
        <div class="card">
            <form action="{{ route('partners.update', $partner) }}" method="POST" class="card" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="card-header">
                    <h4 class="card-title">Form Edit Partner</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="row">
                                <div class="col-md-6 col-xl-12">
                                    <div class="mb-3">
                                        <label class="form-label">Nama Partner</label>
                                        <input type="text" class="form-control" id="name" name="name"
                                            value="{{ old('name', $partner->name) }}" placeholder="Nama Partner" required>
                                        @error('name')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Gambar Partner</label>
                                        @if($partner->image)
                                            <div class="mb-2">
                                                <img src="{{ asset('images/partners/' . $partner->image) }}" 
                                                     alt="{{ $partner->name }}" 
                                                     style="max-width: 200px; max-height: 200px; object-fit: cover; border: 1px solid #ddd; border-radius: 4px;">
                                                <p class="text-muted mt-2">Gambar saat ini</p>
                                            </div>
                                        @endif
                                        <input type="file" class="form-control" name="file" accept="image/*">
                                        <small class="form-hint">Format: JPG, PNG, GIF. Maksimal ukuran file 2MB. Kosongkan jika tidak ingin mengubah gambar.</small>
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

