<x-backoffice.layout.main>
    <x-backoffice.partials.breadcrumb title="Katalog" pretitle="Edit Produk/Jasa" />
    <div class="col-12">
        <div class="card">
            <form action="{{ route('catalog.update', $catalog->id) }}" method="POST" class="card"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="card-header">
                    <h4 class="card-title">Form Edit Produk/Jasa</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="row">
                                <div class="col-md-6 col-xl-12">
                                    <div class="mb-3">
                                        <label class="form-label">Judul Produk/Jasa</label>
                                        <input type="text" class="form-control" id="title" name="title"
                                            value="{{ old('title', $catalog->title) }}" placeholder="Judul Produk/Jasa">
                                        @error('title')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Slug</label>
                                        <input type="text" class="form-control" id="slug" name="slug"
                                            value="{{ old('slug', $catalog->slug) }}" placeholder="Slug">
                                        @error('slug')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Gambar Produk/Jasa</label>
                                        @if ($catalog->image)
                                            <div class="mb-2">
                                                <img src="{{ $catalog->path }}/{{ $catalog->image }}"
                                                    alt="Current Image" class="img-thumbnail" style="max-width: 200px;">
                                                <p class="text-muted small">Gambar saat ini</p>
                                            </div>
                                        @endif
                                        <input type="file" class="form-control" name="file" accept="image/*">
                                        <small class="text-muted">Kosongkan jika tidak ingin mengubah gambar</small>
                                        @error('image')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Kategori</label>
                                        <select class="form-select" name="id_category" required>
                                            <option value="" disabled>Pilih Kategori</option>
                                            @foreach ($categoryProducts as $category)
                                                <option value="{{ $category->id }}"
                                                    {{ old('id_category', $catalog->id_category) == $category->id ? 'selected' : '' }}>
                                                    {{ $category->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('id_category')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Sub Kategori</label>
                                        <select class="form-select" name="id_sub_category">
                                            <option value="">Pilih Sub Kategori</option>
                                            @foreach ($subCategorys as $subcategory)
                                                <option value="{{ $subcategory->id }}"
                                                    {{ old('id_sub_category', $catalog->id_sub_category) == $subcategory->id ? 'selected' : '' }}>
                                                    {{ $subcategory->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('id_sub_category')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Spesifikasi</label>
                                        <textarea class="form-control" name="specification" rows="3"
                                            placeholder="Contoh: Bahan Katun, Ukuran XL, Warna Merah">{{ old('specification', $catalog->specification) }}</textarea>
                                        <small class="form-hint">Pisahkan setiap poin spesifikasi dengan tanda koma
                                            (,)</small>
                                        @error('specification')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Nomor WhatsApp</label>
                                        <input type="text" class="form-control" name="whatsapp"
                                            value="{{ old('whatsapp', $catalog->whatsapp) }}"
                                            placeholder="Contoh: 628123456789">
                                        @error('whatsapp')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Deskripsi</label>
                                        <textarea class="form-control" name="desc" rows="5" placeholder="Deskripsi produk/jasa...">{{ old('desc', $catalog->desc) }}</textarea>
                                        @error('desc')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- User will be auto-filled as auth user, not input here --}}
                                    <input type="hidden" name="id_user" value="{{ auth()->id() }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-end">
                    <div class="d-flex">
                        <a href="{{ route('catalog.index') }}" class="btn btn-link">Batal</a>
                        <button type="submit" class="btn btn-primary ms-auto">Update</button>
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
            if (titleInput && slugInput) {
                titleInput.addEventListener('input', function() {
                    slugInput.value = this.value.toLowerCase().replace(/[^a-z0-9 -]/g, '').replace(/\s+/g,
                        '-').replace(/-+/g, '-');
                });
            }
        });
    </script>
</x-backoffice.partials.scripts>
