<x-backoffice.layout.main>
    <x-backoffice.partials.breadcrumb title="Kontak" pretitle="Kontak"
        createUrl="{{ route('contacts.create') }}" createLabel="Tambah Kontak" />

    <div class="col-12">
        <div class="card">
            <div class="card-body border-bottom py-3">
                <form method="GET" action="{{ url()->current() }}">
                    <div class="d-flex">
                        <div class="text-muted">
                            Show
                            <div class="mx-2 d-inline-block">
                                <select name="limit" class="form-control form-control-sm"
                                    aria-label="Contacts count">
                                    @php $collection = $contacts instanceof \Illuminate\Pagination\LengthAwarePaginator ? $contacts : null; @endphp
                                    @foreach ([5, 10, 25, 50] as $value)
                                        <option value="{{ $value }}"
                                            @if ($collection && (int) request('limit', $collection->perPage()) === $value) selected @endif>
                                            {{ $value }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            entries
                        </div>
                        <div class="ms-auto text-muted">
                            Status:
                            <div class="ms-2 d-inline-block">
                                <select name="status" class="form-control form-control-sm">
                                    <option value="">Semua</option>
                                    <option value="publis" {{ request('status') === 'publis' ? 'selected' : '' }}>
                                        Publis
                                    </option>
                                    <option value="unpublis" {{ request('status') === 'unpublis' ? 'selected' : '' }}>
                                        Unpublis
                                    </option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-sm btn-secondary ms-2">Filter</button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="table-responsive" style="position: relative;">
                <table class="table card-table table-vcenter datatable">
                    <thead>
                        <tr>
                            <th class="w-1">No.</th>
                            <th>Alamat</th>
                            <th>Email</th>
                            <th>Telepon</th>
                            <th>Jam Operasional</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            if ($contacts instanceof \Illuminate\Pagination\LengthAwarePaginator) {
                                $no = ($contacts->currentPage() - 1) * $contacts->perPage() + 1;
                            } else {
                                $no = 1;
                            }
                        @endphp
                        @forelse ($contacts as $contact)
                            <tr>
                                <td><span class="text-muted">{{ $no++ }}</span></td>
                                <td class="text-muted">
                                    {{ \Illuminate\Support\Str::limit($contact->address ?? '-', 60) }}
                                </td>
                                <td class="text-muted">{{ $contact->email ?? '-' }}</td>
                                <td class="text-muted">{{ $contact->phone ?? '-' }}</td>
                                <td class="text-muted">
                                    {{ $contact->weekday_hours ?? '-' }}<br>
                                    {{ $contact->saturday_hours ?? '' }}
                                </td>
                                <td>
                                    @if ($contact->status === 'publis')
                                        <span class="badge bg-success">Publis</span>
                                    @else
                                        <span class="badge bg-secondary">Unpublis</span>
                                    @endif
                                </td>
                                <td class="text-muted">
                                    <span class="dropdown">
                                        <button class="btn dropdown-toggle align-text-top" data-bs-toggle="dropdown"
                                            data-bs-boundary="viewport"
                                            data-bs-popper-config='{"strategy": "fixed"}'>Actions</button>
                                        <div class="dropdown-menu dropdown-menu-start"
                                            style="margin-top: 30px; z-index: 9999; position: absolute;">
                                            <a class="dropdown-item"
                                                href="{{ route('contacts.edit', $contact->id) }}">Edit</a>
                                            <form action="{{ route('contacts.destroy', $contact->id) }}"
                                                method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item"
                                                    onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                                    Hapus
                                                </button>
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

            @if ($contacts instanceof \Illuminate\Pagination\LengthAwarePaginator)
                <div class="card-footer d-flex align-items-center">
                    <p class="m-0 text-muted">
                        Showing
                        <span>
                            {{ ($contacts->currentPage() - 1) * $contacts->perPage() + 1 }}
                        </span>
                        to
                        <span>
                            {{ ($contacts->currentPage() - 1) * $contacts->perPage() + $contacts->count() }}
                        </span>
                        of
                        <span>
                            {{ $contacts->total() }}
                        </span>
                        entries
                    </p>
                    <div class="ms-auto m-0">
                        {{ $contacts->links('vendor.pagination.custom') }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-backoffice.layout.main>


