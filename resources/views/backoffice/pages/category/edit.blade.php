<x-backoffice.layout.main>
    @php
        $current_type = $category->type;
        $pageTitle = 'Kategori';
        if ($current_type === 'catalog') {
            $pageTitle = 'Kategori Katalog';
        } elseif ($current_type === 'content') {
            $pageTitle = 'Kategori Berita';
        } elseif ($current_type === 'sub_catalog') {
            $pageTitle = 'Sub Kategori';
        }
    @endphp
    <x-backoffice.partials.breadcrumb :title="$pageTitle" :pretitle="'Edit ' . $pageTitle" />

    <div class="col-12">
        <div class="card">
        </div>
        <div class="col-12">
            <form action="{{ route('categories.update', $category) }}" method="POST" class="card"
                enctype="multipart/form-data">
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
                                            placeholder="Nama Kategori" value="{{ old('name', $category->name) }}">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Slug</label>
                                        <input type="text" class="form-control" id="slug" name="slug"
                                            placeholder="Slug" value="{{ old('slug', $category->slug) }}" readonly>
                                    </div>

                                    <!-- The type cannot be changed, so we hide it -->
                                    <input type="hidden" name="type" value="{{ $category->type }}">

                                    {{-- This field will only show if we are editing a sub-category --}}
                                    {{-- NOTE: The controller must pass the $parentCategories variable --}}
                                    @if ($category->type === 'sub_catalog' && isset($parentCategories))
                                        <div class="mb-3">
                                            <label class="form-label">Induk Kategori (Katalog)</label>
                                            <select class="form-select" name="parent_id">
                                                <option value="" disabled>Pilih Induk Kategori</option>
                                                @foreach ($parentCategories as $parent)
                                                    <option value="{{ $parent->id }}"
                                                        {{ old('parent_id', $category->parent_id) == $parent->id ? 'selected' : '' }}>
                                                        {{ $parent->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @endif

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
                                                // Ensure the selected icon is in the list if it's an old one
                                                if ($category->icon && !in_array($category->icon, $icons)) {
                                                    array_unshift($icons, $category->icon);
                                                }
                                            @endphp
                                            @foreach ($icons as $icon)
                                                <option value="{{ $icon }}"
                                                    {{ old('icon', $category->icon) == $icon ? 'selected' : '' }}>
                                                    {{ ucfirst(str_replace('-', ' ', $icon)) }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <div class="form-label">Deskripsi</div>
                                        <textarea class="form-control" name="description" rows="3" placeholder="Deskripsi singkat">{{ old('description', $category->description) }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-end">
                        <div class="d-flex">
                            <a href="{{ route('categories.index', ['type' => $category->type]) }}"
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
