<x-backoffice.layout.main>
    <x-backoffice.partials.breadcrumb title="Lisensi" pretitle="Lisensi" createUrl="{{ route('licenses.create') }}"
        createLabel="Tambah Lisensi" />
    <div class="col-12">
        <div class="card">
            <div class="card-body border-bottom py-3">
                <form method="GET" action="{{ url()->current() }}">
                    <div class="d-flex">
                        <div class="text-muted">
                            Show
                            <div class="mx-2 d-inline-block">
                                <select name="limit" class="form-control form-control-sm" aria-label="Licenses count">
                                    @foreach ([5, 10, 25, 50] as $value)
                                        <option value="{{ $value }}"
                                            {{ (int) request('limit', $licenses->perPage()) === $value ? 'selected' : '' }}>
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
                                    class="form-control form-control-sm" aria-label="Search licenses">
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
                            <th>Gambar</th>
                            <th>Nama</th>
                            <th>Kode</th>
                            <th>Tipe</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            // Ambil nomor pertama pada halaman ini (untuk penomoran tabel yang konsisten dengan pagination)
                            $no = ($licenses->currentPage() - 1) * $licenses->perPage() + 1;
                        @endphp
                        @forelse ($licenses as $license)
                            <tr>
                                <td><span class="text-muted">{{ $no++ }}</span></td>
                                <td>
                                    @if (!empty($license->file))
                                        @php
                                            $ext = pathinfo($license->file, PATHINFO_EXTENSION);
                                        @endphp
                                        @if (in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                                            <img src="{{ asset('images/licenses/' . $license->file) }}"
                                                alt="{{ $license->name }}" class="rounded border"
                                                style="width: 50px; height: 50px; object-fit: cover;">
                                        @else
                                            <span class="badge bg-secondary">{{ strtoupper($ext) }}</span>
                                        @endif
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="text-muted">{{ $license->name ?? '-' }}</td>
                                <td class="text-muted">{{ $license->code ?? '-' }}</td>
                                <td class="text-muted">{{ $license->type ?? '-' }}</td>
                                <td class="text-muted">
                                    <span class="dropdown">
                                        <button class="btn dropdown-toggle align-text-top" data-bs-toggle="dropdown"
                                            data-bs-boundary="viewport"
                                            data-bs-popper-config='{"strategy": "fixed"}'>Actions</button>
                                        <div class="dropdown-menu dropdown-menu-start">
                                            <a class="dropdown-item"
                                                href="{{ route('licenses.edit', $license->id) }}">Edit</a>
                                            <form action="{{ route('licenses.destroy', $license->id) }}" method="POST"
                                                style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item"
                                                    onclick="return confirm('Apakah Anda yakin ingin menghapus lisensi ini?')">Hapus</button>
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
                <p class="m-0 text-muted">
                    Showing
                    <span>
                        {{ ($licenses->currentPage() - 1) * $licenses->perPage() + 1 }}
                    </span>
                    to
                    <span>
                        {{ ($licenses->currentPage() - 1) * $licenses->perPage() + $licenses->count() }}
                    </span>
                    of
                    <span>
                        {{ $licenses->total() }}
                    </span>
                    entries
                </p>
                <div class="ms-auto m-0">
                    {{ $licenses->links('vendor.pagination.custom') }}
                </div>
            </div>
        </div>
    </div>
</x-backoffice.layout.main>
