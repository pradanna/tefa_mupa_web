<x-backoffice.layout.main>
    <x-backoffice.partials.breadcrumb title="Berita" pretitle="Edit Berita" />
    <div class="col-12">
        <div class="card">
            <form action="{{ route('articles.update', $news) }}" method="POST" class="card" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="card-header">
                    <h4 class="card-title">Form Edit Berita</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="row">
                                <div class="col-md-6 col-xl-12">
                                    <div class="mb-3">
                                        <label class="form-label">Judul Berita</label>
                                        <input type="text" class="form-control" id="title" name="title"
                                            value="{{ old('title', $news->title) }}" placeholder="Judul Berita">
                                        @error('title')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Slug</label>
                                        <input type="text" class="form-control" id="slug" name="slug"
                                            value="{{ old('slug', $news->slug) }}" placeholder="Slug">
                                        @error('slug')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Kategori</label>
                                        <select class="form-select" name="id_category" required>
                                            <option value="" disabled>Pilih Kategori</option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}" {{ old('id_category', $news->id_category) == $category->id ? 'selected' : '' }}>
                                                    {{ $category->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('id_category')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Tanggal Berita</label>
                                        <input type="date" class="form-control" name="date" value="{{ old('date', $news->date) }}">
                                        @error('date')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Status</label>
                                        <select class="form-select" name="status" required>
                                            <option value="publis" {{ old('status', $news->status) == 'publis' ? 'selected' : '' }}>Publis</option>
                                            <option value="unpublis" {{ old('status', $news->status) == 'unpublis' ? 'selected' : '' }}>Unpublis</option>
                                        </select>
                                        @error('status')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Gambar Berita</label>
                                        <input type="file" class="form-control" name="file" accept="image/*" id="file-input">
                                        @error('file')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3" id="preview-wrapper">
                                        <label class="form-label">Preview Gambar Saat Ini</label>
                                        <div>
                                            <img
                                                id="image-preview"
                                                src="{{ isset($news->path, $news->image) ? rtrim($news->path, '/') . '/' . $news->image : '' }}"
                                                alt="Preview Gambar"
                                                class="img-fluid rounded"
                                                style="max-width: 300px; max-height: 200px;@if(!$news->image) display:none;@endif"
                                            >
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Konten Berita</label>
                                        <textarea class="form-control" name="content" rows="5" placeholder="Isi berita...">{{ old('content', $news->content) }}</textarea>
                                        @error('content')
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
                        <a href="{{ route('articles.index') }}" class="btn btn-link">Batal</a>
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
            var titleInput = document.getElementById('title');
            var slugInput = document.getElementById('slug');
            var fileInput = document.getElementById('file-input');
            var previewImg = document.getElementById('image-preview');
            var previewWrapper = document.getElementById('preview-wrapper');

            // Auto-generate slug from title
            if (titleInput && slugInput) {
                titleInput.addEventListener('input', function() {
                    slugInput.value = titleInput.value.toLowerCase().replace(/\s+/g, '-').replace(/[^\w\-]+/g, '');
                });
            }

            // Image preview
            if (fileInput && previewImg) {
                fileInput.addEventListener('change', function(e) {
                    if (e.target.files && e.target.files[0]) {
                        var reader = new FileReader();
                        reader.onload = function(ev) {
                            previewImg.src = ev.target.result;
                            previewImg.style.display = 'block';
                            previewWrapper.style.display = '';
                        };
                        reader.readAsDataURL(e.target.files[0]);
                    } else {
                        @if(!$news->image)
                            previewImg.src = '';
                            previewImg.style.display = 'none';
                            previewWrapper.style.display = 'none';
                        @else
                            previewImg.src = "{{ isset($news->path, $news->image) ? rtrim($news->path, '/') . '/' . $news->image : '' }}";
                            previewImg.style.display = 'block';
                            previewWrapper.style.display = '';
                        @endif
                    }
                });
            }
        });
    </script>
</x-backoffice.partials.scripts>

