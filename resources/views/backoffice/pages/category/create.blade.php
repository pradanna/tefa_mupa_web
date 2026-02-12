<x-backoffice.layout.main>
    @php
        $current_type = request('type', 'catalog');
        $pageTitle = 'Kategori';
        if ($current_type === 'catalog') {
            $pageTitle = 'Kategori Katalog';
        } elseif ($current_type === 'content') {
            $pageTitle = 'Kategori Berita';
        } elseif ($current_type === 'sub_catalog') {
            $pageTitle = 'Sub Kategori';
        }
    @endphp

    <x-backoffice.partials.breadcrumb :title="$pageTitle" :pretitle="'Tambah ' . $pageTitle" />

    <div class="col-12">
        <div class="card">
        </div>
        <div class="col-12">
            <form action="{{ route('categories.store') }}" method="POST" class="card" enctype="multipart/form-data">
                @csrf
                <div class="card-header">
                    <h4 class="card-title">Form Tambah Kategori</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="row">
                                <div class="col-md-6 col-xl-12">
                                    <div class="mb-3">
                                        <label class="form-label">Nama</label>
                                        <input type="text" class="form-control" id="name" name="name"
                                            placeholder="Nama Kategori" value="{{ old('name') }}">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Slug</label>
                                        <input type="text" class="form-control" id="slug" name="slug"
                                            placeholder="Slug" value="{{ old('slug') }}" readonly>
                                    </div>

                                    <!-- The type is now determined by the tab, so we hide it -->
                                    <input type="hidden" name="type" value="{{ $current_type }}">


                                    <div class="mb-3">
                                        <label class="form-label">Ikon</label>
                                        <select class="form-select" name="icon" id="select-icon">
                                            <option value="">Pilih Ikon</option>
                                            @php
                                                $icons = [
                                                    // General & Produk
                                                    'tags',
                                                    'archive',
                                                    'folder',
                                                    'package',
                                                    'layout-list',
                                                    'bookmark',
                                                    'library',
                                                    'shopping-basket',
                                                    'box',
                                                    // Otomotif
                                                    'car',
                                                    'wrench',
                                                    'settings-2',
                                                    'truck',
                                                    // Komputer & Desain
                                                    'cpu',
                                                    'server',
                                                    'laptop',
                                                    'hard-drive',
                                                    'smartphone',
                                                    'database',
                                                    'pencil-ruler',
                                                    'palette',
                                                    'paintbrush',
                                                    'pen-tool',
                                                    'camera',
                                                    // Berita & Konten
                                                    'book-open-text',
                                                    'newspaper',
                                                    'clapperboard',
                                                ];
                                            @endphp
                                            @foreach ($icons as $icon)
                                                <option value="{{ $icon }}"
                                                    {{ old('icon') == $icon ? 'selected' : '' }}>
                                                    {{ ucfirst(str_replace('-', ' ', $icon)) }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <div class="form-label">Deskripsi</div>
                                        <textarea class="form-control" name="description" rows="3" placeholder="Deskripsi singkat">{{ old('description') }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-end">
                        <div class="d-flex">
                            <a href="{{ route('categories.index', ['type' => $current_type]) }}"
                                class="btn btn-link">Batal</a>
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
            // Slug generator
            var nameInput = document.getElementById('name');
            var slugInput = document.getElementById('slug');
            if (nameInput && slugInput) {
                nameInput.addEventListener('input', function() {
                    slugInput.value = this.value.toLowerCase().replace(/[^a-z0-9 -]/g, '').replace(
                        /\s+/g, '-').replace(/-+/g, '-');
                });
            }

            // Tom-select for icons
            var tomSelect = new TomSelect('#select-icon', {
                copyClassesToDropdown: false,
                dropdownParent: 'body',
                controlInput: '<input>',
                render: {
                    item: function(data, escape) {
                        if (!data.value) return '<div>' + escape(data.text) + '</div>';
                        return '<div><i data-lucide="' + escape(data.value) +
                            '" class="icon me-2"></i><span>' + escape(data.text) + '</span></div>';
                    },
                    option: function(data, escape) {
                        if (!data.value) return '<div>' + escape(data.text) + '</div>';
                        return '<div><i data-lucide="' + escape(data.value) +
                            '" class="icon me-2"></i><span>' + escape(data.text) + '</span></div>';
                    },
                },
            });

            const reapplyLucide = () => setTimeout(() => lucide.createIcons(), 1);
            tomSelect.on('initialize', reapplyLucide);
            tomSelect.on('change', reapplyLucide);
            tomSelect.on('dropdown_open', reapplyLucide);
        });
    </script>
</x-backoffice.partials.scripts>
