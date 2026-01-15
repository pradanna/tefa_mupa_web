<x-backoffice.layout.main>
    <x-backoffice.partials.breadcrumb title="Organisasi" pretitle="Edit Organisasi" />
    <div class="col-12">
        <div class="card">
            <form action="{{ route('organizations.update', $organization) }}" method="POST" class="card" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="card-header">
                    <h4 class="card-title">Form Edit Organisasi</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="row">
                                <div class="col-md-6 col-xl-12">
                                    <div class="mb-3">
                                        <label class="form-label">Nama <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="name" name="name"
                                            value="{{ old('name', $organization->name) }}" placeholder="Nama Lengkap" required>
                                        @error('name')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Posisi <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="position" name="position"
                                            value="{{ old('position', $organization->position) }}" placeholder="Posisi/Jabatan" required>
                                        @error('position')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Email</label>
                                        <input type="email" class="form-control" id="email" name="email"
                                            value="{{ old('email', $organization->email) }}" placeholder="email@example.com">
                                        @error('email')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Instagram</label>
                                        <input type="text" class="form-control" id="instagram" name="instagram"
                                            value="{{ old('instagram', $organization->instagram) }}" placeholder="@username">
                                        @error('instagram')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">LinkedIn</label>
                                        <input type="text" class="form-control" id="linkedin" name="linkedin"
                                            value="{{ old('linkedin', $organization->linkedin) }}" placeholder="LinkedIn URL">
                                        @error('linkedin')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Order <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" id="order" name="order"
                                            value="{{ old('order', $organization->order) }}" placeholder="Urutan tampil" required min="1">
                                        @error('order')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Gambar</label>
                                        <input type="file" class="form-control" name="image" accept="image/*" id="image-input">
                                        @error('image')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">Kosongkan jika tidak ingin mengubah gambar</small>
                                    </div>
                                    <div class="mb-3" id="preview-wrapper">
                                        <label class="form-label">Preview Gambar Saat Ini</label>
                                        <div>
                                            <img
                                                id="image-preview"
                                                src="{{ isset($organization->path, $organization->image) ? rtrim($organization->path, '/') . '/' . $organization->image : '' }}"
                                                alt="Preview Gambar"
                                                class="img-fluid rounded"
                                                style="max-width: 300px; max-height: 200px;@if(!$organization->image) display:none;@endif"
                                            >
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-end">
                    <div class="d-flex">
                        <a href="{{ route('organizations.index') }}" class="btn btn-link">Batal</a>
                        <button type="submit" class="btn btn-primary ms-auto">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-backoffice.layout.main>
<x-backoffice.partials.scripts>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var imageInput = document.getElementById('image-input');
            var previewImg = document.getElementById('image-preview');
            var previewWrapper = document.getElementById('preview-wrapper');

            // Image preview
            if (imageInput && previewImg) {
                imageInput.addEventListener('change', function(e) {
                    if (e.target.files && e.target.files[0]) {
                        var reader = new FileReader();
                        reader.onload = function(ev) {
                            previewImg.src = ev.target.result;
                            previewImg.style.display = 'block';
                            previewWrapper.style.display = '';
                        };
                        reader.readAsDataURL(e.target.files[0]);
                    } else {
                        @if(!$organization->image)
                            previewImg.src = '';
                            previewImg.style.display = 'none';
                            previewWrapper.style.display = 'none';
                        @else
                            previewImg.src = "{{ isset($organization->path, $organization->image) ? rtrim($organization->path, '/') . '/' . $organization->image : '' }}";
                            previewImg.style.display = 'block';
                            previewWrapper.style.display = '';
                        @endif
                    }
                });
            }
        });
    </script>
</x-backoffice.partials.scripts>

