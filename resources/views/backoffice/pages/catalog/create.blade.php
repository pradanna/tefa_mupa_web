<x-backoffice.layout.main>
    <x-backoffice.partials.breadcrumb title="Katalog" pretitle="Tambah Produk/Jasa" />
    <div class="col-12">
        <div class="card">
            <form action="{{ route('catalog.store') }}" method="POST" class="card" enctype="multipart/form-data">
                @csrf
                <div class="card-header">
                    <h4 class="card-title">Form Tambah Produk/Jasa</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="row">
                                <div class="col-md-6 col-xl-12">
                                    <div class="mb-3">
                                        <label class="form-label">Judul Produk/Jasa</label>
                                        <input type="text" class="form-control" id="title" name="title"
                                            value="{{ old('title') }}" placeholder="Judul Produk/Jasa">
                                        @error('title')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Slug</label>
                                        <input type="text" class="form-control" id="slug" name="slug"
                                            value="{{ old('slug') }}" placeholder="Slug (Auto-generated)">
                                        @error('slug')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Gambar Produk/Jasa</label>
                                        <input type="file" class="form-control" name="file" accept="image/*">
                                        @error('image')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Kategori</label>
                                        <select class="form-select" name="id_category" required>
                                            <option value="" disabled selected>Pilih Kategori</option>
                                            @foreach ($categoryProducts as $category)
                                                <option value="{{ $category->id }}"
                                                    {{ old('id_category') == $category->id ? 'selected' : '' }}>
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
                                            <option value="" selected>Pilih Sub Kategori</option>
                                            @foreach ($subCategorys as $subcategory)
                                                <option value="{{ $subcategory->id }}"
                                                    {{ old('id_sub_category') == $subcategory->id ? 'selected' : '' }}>
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
                                            placeholder="Contoh: Bahan Katun, Ukuran XL, Warna Merah">{{ old('specification') }}</textarea>
                                        <small class="form-hint">Pisahkan setiap poin spesifikasi dengan tanda koma
                                            (,)</small>
                                        @error('specification')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Nomor WhatsApp</label>
                                        <input type="text" class="form-control" name="whatsapp"
                                            value="{{ old('whatsapp') }}" placeholder="Contoh: 628123456789">
                                        @error('whatsapp')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Deskripsi</label>
                                        <textarea class="form-control" name="desc" rows="5" placeholder="Deskripsi produk/jasa...">{{ old('desc') }}</textarea>
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
            if (titleInput && slugInput) {
                titleInput.addEventListener('input', function() {
                    slugInput.value = this.value.toLowerCase().replace(/[^a-z0-9 -]/g, '').replace(/\s+/g,
                        '-').replace(/-+/g, '-');
                });
            }
        });
    </script>
</x-backoffice.partials.scripts>
