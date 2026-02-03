<x-backoffice.layout.main>
    <x-backoffice.partials.breadcrumb title="Promosi" pretitle="Promosi" createUrl="{{ route('promotions.create') }}"
        createLabel="Tambah Promosi" />
    <div class="col-12">
        <div class="card">
            <div class="card-body border-bottom py-3">
                <div class="d-flex">
                    <div class="text-muted">
                        Show
                        <div class="mx-2 d-inline-block">
                            <input type="text" class="form-control form-control-sm" value="{{ $promotions->perPage() }}" size="3"
                                aria-label="Promotions count">
                        </div>
                        entries
                    </div>
                    <div class="ms-auto text-muted">
                        Search:
                        <div class="ms-2 d-inline-block">
                            <input type="text" class="form-control form-control-sm" aria-label="Search promotions">
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
                            <th>Deskripsi</th>
                            <th>Kode</th>
                            <th>Tanggal Kadaluarsa</th>
                            <th>Gambar</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            // Ambil nomor pertama pada halaman ini (untuk penomoran tabel yang konsisten dengan pagination)
                            $no = ($promotions->currentPage() - 1) * $promotions->perPage() + 1;
                        @endphp
                        @forelse ($promotions as $promotion)
                            <tr>
                                <td><span class="text-muted">{{ $no++ }}</span></td>
                                <td class="text-muted">{{ $promotion->name ?? '-' }}</td>
                                <td class="text-muted">{{ \Illuminate\Support\Str::limit($promotion->desc ?? '-', 50) }}</td>
                                <td class="text-muted">{{ $promotion->code ?? '-' }}</td>
                                <td class="text-muted">{{ $promotion->expired ? \Carbon\Carbon::parse($promotion->expired)->format('d/m/Y') : '-' }}</td>
                                <td class="text-muted">
                                    @if($promotion->image)
                                        <img src="{{ asset('storage/images/promotions/' . $promotion->image) }}" 
                                             alt="{{ $promotion->name }}" 
                                             style="max-width: 100px; max-height: 60px; object-fit: cover;">
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="text-muted position-relative">
                                    <span class="dropdown">
                                        <button class="btn dropdown-toggle align-text-top" data-bs-toggle="dropdown"
                                            data-bs-boundary="viewport">Actions</button>
                                        <div class="dropdown-menu dropdown-menu-start"
                                            style="margin-top: 30px;">
                                            <a class="dropdown-item" href="{{ route('promotions.edit', $promotion->id) }}">Edit</a>
                                            <form action="{{ route('promotions.destroy', $promotion->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item" onclick="return confirm('Are you sure you want to delete this promotion?')">Delete</button>
                                            </form>
                                        </div>
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <x-backoffice.table.empty colspan="7" />
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer d-flex align-items-center">
                <p class="m-0 text-muted">
                    Showing
                    <span>
                        {{ ($promotions->currentPage() - 1) * $promotions->perPage() + 1 }}
                    </span>
                    to
                    <span>
                        {{ ($promotions->currentPage() - 1) * $promotions->perPage() + $promotions->count() }}
                    </span>
                    of
                    <span>
                        {{ $promotions->total() }}
                    </span>
                    entries
                </p>
                <div class="ms-auto m-0">
                    {{ $promotions->links('vendor.pagination.custom') }}
                </div>
            </div>
        </div>
    </div>
</x-backoffice.layout.main>

