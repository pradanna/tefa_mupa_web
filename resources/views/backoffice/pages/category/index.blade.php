<x-backoffice.layout.main>
    <x-backoffice.partials.breadcrumb title="Kategori" pretitle="Kategori" createUrl="{{ route('categories.create') }}"
        createLabel="Tambah Kategori" />
    <div class="col-12">
        <div class="card">
            <div class="card-body border-bottom py-3">
                <div class="d-flex">
                    <div class="text-muted">
                        Show
                        <div class="mx-2 d-inline-block">
                            <input type="text" class="form-control form-control-sm" value="8" size="3"
                                aria-label="Slider count">
                        </div>
                        entries
                    </div>
                    <div class="ms-auto text-muted">
                        Search:
                        <div class="ms-2 d-inline-block">
                            <input type="text" class="form-control form-control-sm" aria-label="Search slider">
                        </div>
                    </div>
                </div>
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
                            <th>Type</th>
                            <th>Slug</th>
                            <th>Deskripsi</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $no = 1;
                        @endphp
                        @php
                            // Ambil nomor pertama pada halaman ini (untuk penomoran tabel yang konsisten dengan pagination)
                            $no = ($categorys->currentPage() - 1) * $categorys->perPage() + 1;
                        @endphp
                        @forelse ($categorys as $category)
                            <tr>
                                <td><span class="text-muted">{{ $no++ }}</span></td>
                                <td class="text-muted">{{ $category->name ?? '-' }}</td>
                                <td class="text-muted">{{ $category->type ?? '-' }}</td>
                                <td class="text-muted">{{ $category->slug ?? '-' }}</td>
                                <td class="text-muted">{{ $category->description ?? '-' }}</td>
                                <td class="text-muted position-relative">
                                    <span class="dropdown">
                                        <button class="btn dropdown-toggle align-text-top" data-bs-toggle="dropdown"
                                            data-bs-boundary="viewport">Actions</button>
                                        <div class="dropdown-menu dropdown-menu-start"
                                            style="margin-top: 30px;">
                                            <a class="dropdown-item" href="{{ route('categories.edit', $category->id) }}">Edit</a>
                                            <form action="{{ route('categories.destroy', $category->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item" onclick="return confirm('Are you sure you want to delete this category?')">Delete</button>
                                            </form>
                                        </div>
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <x-backoffice.table.empty
                            colspan="6"
                            />
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer d-flex align-items-center">
                <p class="m-0 text-muted">
                    Showing
                    <span>
                        {{ ($categorys->currentPage() - 1) * $categorys->perPage() + 1 }}
                    </span>
                    to
                    <span>
                        {{ ($categorys->currentPage() - 1) * $categorys->perPage() + $categorys->count() }}
                    </span>
                    of
                    <span>
                        {{ $categorys->total() }}
                    </span>
                    entries
                </p>
                <div class="ms-auto m-0">
                    {{ $categorys->links('vendor.pagination.custom') }}
                </div>
            </div>
        </div>
    </div>
</x-backoffice.layout.main>
