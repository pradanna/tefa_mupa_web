<x-backoffice.layout.main>
    @php
        // Define types for cleaner code and easy modification
        $types = [
            'catalog' => 'Kategori Katalog',
            'content' => 'Kategori Berita',
            'sub_catalog' => 'Sub Kategori',
        ];
        $current_type = request('type', 'catalog');
        $current_title = $types[$current_type] ?? 'Kategori';
    @endphp

    <x-backoffice.partials.breadcrumb :title="$current_title" pretitle="Manajemen Kategori"
        createUrl="{{ route('categories.create', ['type' => $current_type]) }}" :createLabel="'Tambah ' . $current_title" />

    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <ul class="nav nav-tabs card-header-tabs">
                    @foreach ($types as $type => $title)
                        <li class="nav-item">
                            <a class="nav-link {{ $current_type === $type ? 'active' : '' }}"
                                href="{{ route('categories.index', ['type' => $type]) }}">
                                {{ $title }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="card-body border-bottom py-3">
                <form method="GET" action="{{ url()->current() }}">
                    <div class="d-flex">
                        <div class="text-muted">
                            Show
                            <div class="mx-2 d-inline-block">
                                <select name="limit" class="form-control form-control-sm" aria-label="Category count"
                                    onchange="this.form.submit()">
                                    @foreach ([5, 10, 25, 50] as $value)
                                        <option value="{{ $value }}"
                                            {{ (int) request('limit', $categorys->perPage()) === $value ? 'selected' : '' }}>
                                            {{ $value }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            entries
                        </div>
                        <div class="ms-auto text-muted">
                            Search:
                            <div class="ms-2 d-inline-block">
                                <input type="text" name="search" value="{{ request('search') }}"
                                    class="form-control form-control-sm" aria-label="Search category">
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="type" value="{{ $current_type }}">
                    <input type="hidden" name="search_column" value="name">
                </form>
            </div>
            <div class="table-responsive">
                <table class="table card-table table-vcenter text-nowrap datatable">
                    <thead>
                        <tr>
                            <th class="w-1">No.
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm icon-thick" width="24"
                                    height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                    fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M6 15l6 -6l6 6" />
                                </svg>
                            </th>
                            <th>Nama</th>
                            <th>Slug</th>
                            <th>Deskripsi</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            // Ambil nomor pertama pada halaman ini (untuk penomoran tabel yang konsisten dengan pagination)
                            $no = ($categorys->currentPage() - 1) * $categorys->perPage() + 1;
                        @endphp
                        @forelse ($categorys as $category)
                            <tr>
                                <td><span class="text-muted">{{ $no++ }}</span></td>
                                <td class="text-muted">{{ $category->name ?? '-' }}</td>
                                <td class="text-muted">{{ $category->slug ?? '-' }}</td>
                                <td class="text-muted">
                                    {{ \Illuminate\Support\Str::limit($category->description, 50) ?? '-' }}</td>
                                <td class="text-muted">
                                    <span class="dropdown ">
                                        <button class="btn dropdown-toggle align-text-top" data-bs-toggle="dropdown"
                                            data-bs-boundary="viewport" data-bs-popper-config='{"strategy": "fixed"}'>
                                            Actions
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-start">
                                            <a class="dropdown-item"
                                                href="{{ route('categories.edit', $category->id) }}">Edit</a>
                                            <form action="{{ route('categories.destroy', $category->id) }}"
                                                method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item"
                                                    onclick="return confirm('Are you sure you want to delete this category?')">Delete</button>
                                            </form>
                                        </div>
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <x-backoffice.table.empty colspan="5" />
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer d-flex align-items-center">
                @if ($categorys->total() > 0)
                    <p class="m-0 text-muted">
                        Showing <span>{{ $categorys->firstItem() }}</span> to
                        <span>{{ $categorys->lastItem() }}</span> of <span>{{ $categorys->total() }}</span> entries
                    </p>
                @endif
                <div class="ms-auto m-0">
                    {{ $categorys->appends(request()->query())->links('vendor.pagination.custom') }}
                </div>
            </div>
        </div>
    </div>
</x-backoffice.layout.main>
