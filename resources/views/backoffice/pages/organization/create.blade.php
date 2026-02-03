<x-backoffice.layout.main>
    <x-backoffice.partials.breadcrumb title="Organisasi" pretitle="Tambah Organisasi" />
    <div class="col-12">
        <div class="card">
            <form action="{{ route('organizations.store') }}" method="POST" class="card" enctype="multipart/form-data">
                @csrf
                <div class="card-header">
                    <h4 class="card-title">Form Tambah Organisasi</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="row">
                                <div class="col-md-6 col-xl-12">
                                    <div class="mb-3">
                                        <label class="form-label">Nama <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="name" name="name"
                                            value="{{ old('name') }}" placeholder="Nama Lengkap" required>
                                        @error('name')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Posisi <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="position" name="position"
                                            value="{{ old('position') }}" placeholder="Posisi/Jabatan" required>
                                        @error('position')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Email</label>
                                        <input type="email" class="form-control" id="email" name="email"
                                            value="{{ old('email') }}" placeholder="email@example.com">
                                        @error('email')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Instagram</label>
                                        <input type="text" class="form-control" id="instagram" name="instagram"
                                            value="{{ old('instagram') }}" placeholder="@username">
                                        @error('instagram')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">LinkedIn</label>
                                        <input type="text" class="form-control" id="linkedin" name="linkedin"
                                            value="{{ old('linkedin') }}" placeholder="LinkedIn URL">
                                        @error('linkedin')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Order <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" id="order" name="order"
                                            value="{{ old('order') }}" placeholder="Urutan tampil" required min="1">
                                        @error('order')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Gambar <span class="text-danger">*</span></label>
                                        <input type="file" class="form-control" name="image" accept="image/*" id="image-input" required>
                                        @error('image')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3" id="preview-wrapper" style="display: none;">
                                        <label class="form-label">Preview Gambar</label>
                                        <div>
                                            <img
                                                id="image-preview"
                                                src=""
                                                alt="Preview Gambar"
                                                class="img-fluid rounded"
                                                style="max-width: 300px; max-height: 200px;"
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
                            previewWrapper.style.display = 'block';
                        };
                        reader.readAsDataURL(e.target.files[0]);
                    } else {
                        previewImg.src = '';
                        previewWrapper.style.display = 'none';
                    }
                });
            }
        });
    </script>
</x-backoffice.partials.scripts>

