<x-backoffice.layout.main>
    <x-backoffice.partials.breadcrumb title="Kategori" pretitle="Edit kategori" />
    <div class="col-12">
        <div class="card">
        </div>
        <div class="col-12">
            <form action="{{ route('categories.update',$category) }}" method="POST" class="card" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="card-header">
                    <h4 class="card-title">Form Edit Kategori</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="row">
                                <div class="col-md-6 col-xl-12">
                                    <div class="mb-3">
                                        <label class="form-label">Nama Kategori</label>
                                        <input type="text" class="form-control" id="name" name="name"
                                            placeholder="Nama Kategori" value="{{ $category->name ?? '' }}">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Slug</label>
                                        <input type="text" class="form-control" id="slug" name="slug" placeholder="Slug" value="{{ $category->slug ?? '' }}">
                                    </div
                                    <div class="mb-3">
                                        <div class="form-label">Type</div>
                                        <select class="form-select" name="type">
                                         <option value="0" >Pilih Type</option>
                                          <option value="catalog" {{ $category->type === 'catalog' ? 'selected' : '' }}>Katalog</option>
                                          <option value="content" {{ $category->type === 'content' ? 'selected' : '' }}>Berita</option>
                                        </select>
                                      </div>
                                    <div class="mb-3">
                                        <label class="form-label">Icon</label>
                                        <input type="text" class="form-control" name="icon" placeholder="icon" value="{{ $category->icon ?? '' }}">
                                    </div>
                                    <div class="mb-3">
                                        <div class="form-label">Deskripsi</div>
                                        <input type="text" class="form-control" name="description"
                                            placeholder="Deskripsi" value="{{ $category->description ?? '' }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-end">
                        <div class="d-flex">
                            <a href="{{ route('sliders.index') }}" class="btn btn-link">Batal</a>
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
            var nameInput = document.getElementById('name');
            var slugInput = document.getElementById('slug');
            if (nameInput && slugInput) {
                nameInput.addEventListener('input', function() {
                    slugInput.value = nameInput.value.replace(/\s+/g, '_');
                });
            }
        });
    </script>
</x-backoffice.partials.scripts>
