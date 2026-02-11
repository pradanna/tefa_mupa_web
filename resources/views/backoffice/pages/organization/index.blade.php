<x-backoffice.layout.main>
    <x-backoffice.partials.breadcrumb title="Organisasi" pretitle="Organisasi" createUrl="{{ route('organizations.create') }}"
        createLabel="Tambah Organisasi" />
    <div class="col-12">
        <div class="card">
            <div class="card-body border-bottom py-3">
                <form method="GET" action="{{ url()->current() }}">
                    <div class="d-flex">
                        <div class="text-muted">
                            Show
                            <div class="mx-2 d-inline-block">
                                <select name="limit" class="form-control form-control-sm" aria-label="Organization count">
                                    @foreach ([5, 10, 25, 50] as $value)
                                        <option value="{{ $value }}" {{ (int) request('limit', $organizations->perPage()) === $value ? 'selected' : '' }}>
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
                                <input
                                    type="text"
                                    name="search"
                                    value="{{ request('search') }}"
                                    class="form-control form-control-sm"
                                    aria-label="Search organization">
                            </div>
                        </div>
                    </div>
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
                            <th>Posisi</th>
                            <th>Email</th>
                            <th>Order</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            // Ambil nomor pertama pada halaman ini (untuk penomoran tabel yang konsisten dengan pagination)
                            $no = ($organizations->currentPage() - 1) * $organizations->perPage() + 1;
                        @endphp
                        @forelse ($organizations as $org)
                            <tr>
                                <td><span class="text-muted">{{ $no++ }}</span></td>
                                <td class="text-muted">{{ $org->name ?? '-' }}</td>
                                <td class="text-muted">{{ $org->position ?? '-' }}</td>
                                <td class="text-muted">{{ $org->email ?? '-' }}</td>
                                <td class="text-muted">{{ $org->order ?? '-' }}</td>
                                <td class="text-muted position-relative">
                                    <span class="dropdown">
                                        <button class="btn dropdown-toggle align-text-top" data-bs-toggle="dropdown"
                                            data-bs-boundary="viewport">Actions</button>
                                        <div class="dropdown-menu dropdown-menu-start"
                                            style="margin-top: 30px;">
                                            <a class="dropdown-item" href="{{ route('organizations.edit', $org->id) }}">Edit</a>
                                            <form action="{{ route('organizations.destroy', $org->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item" onclick="return confirm('Are you sure you want to delete this organization?')">Delete</button>
                                            </form>
                                        </div>
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <x-backoffice.table.empty colspan="6" />
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer d-flex align-items-center">
                <p class="m-0 text-muted">
                    Showing
                    <span>
                        {{ ($organizations->currentPage() - 1) * $organizations->perPage() + 1 }}
                    </span>
                    to
                    <span>
                        {{ ($organizations->currentPage() - 1) * $organizations->perPage() + $organizations->count() }}
                    </span>
                    of
                    <span>
                        {{ $organizations->total() }}
                    </span>
                    entries
                </p>
                <div class="ms-auto m-0">
                    {{ $organizations->links('vendor.pagination.custom') }}
                </div>
            </div>
        </div>
    </div>
</x-backoffice.layout.main>

