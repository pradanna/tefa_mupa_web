<x-backoffice.layout.main>
    <x-backoffice.partials.breadcrumb title="Promosi" pretitle="Tambah Promosi" />
    <div class="col-12">
        <div class="card">
            <form action="{{ route('promotions.store') }}" method="POST" class="card" enctype="multipart/form-data">
                @csrf
                <div class="card-header">
                    <h4 class="card-title">Form Tambah Promosi</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="row">
                                <div class="col-md-6 col-xl-12">
                                    <div class="mb-3">
                                        <label class="form-label">Nama Promosi</label>
                                        <input type="text" class="form-control" id="name" name="name"
                                            value="{{ old('name') }}" placeholder="Nama Promosi" required>
                                        @error('name')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Deskripsi</label>
                                        <textarea class="form-control" name="desc" rows="3" placeholder="Deskripsi promosi..." required>{{ old('desc') }}</textarea>
                                        @error('desc')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Kode Promosi</label>
                                        <input type="text" class="form-control" id="code" name="code"
                                            value="{{ old('code') }}" placeholder="Kode Promosi" required>
                                        @error('code')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Tanggal Kadaluarsa</label>
                                        <input type="date" class="form-control" name="expired" value="{{ old('expired') }}" required>
                                        @error('expired')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Gambar Promosi</label>
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
                        <a href="{{ route('promotions.index') }}" class="btn btn-link">Batal</a>
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

