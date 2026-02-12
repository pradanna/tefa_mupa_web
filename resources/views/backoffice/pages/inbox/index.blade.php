<x-backoffice.layout.main>
    <x-backoffice.partials.breadcrumb title="Inbox" pretitle="Pesan Masuk" />
    <div class="col-12">
        <div class="card">
            <div class="card-body border-bottom py-3">
                <form method="GET" action="{{ url()->current() }}">
                    <div class="d-flex">
                        <div class="text-muted">
                            Show
                            <div class="mx-2 d-inline-block">
                                <select name="limit" class="form-control form-control-sm" aria-label="Inbox count">
                                    @foreach ([5, 10, 25, 50] as $value)
                                        <option value="{{ $value }}"
                                            {{ (int) request('limit', $inbox->perPage()) === $value ? 'selected' : '' }}>
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
                                    class="form-control form-control-sm" aria-label="Search inbox">
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="search_column" value="full_name">
                </form>
            </div>
            <div class="table-responsive">
                <table class="table card-table table-vcenter text-nowrap datatable">
                    <thead>
                        <tr>
                            <th class="w-1">No.</th>
                            <th>Nama Lengkap</th>
                            <th>No. HP</th>
                            <th>Kategori</th>
                            <th>Pesan</th>
                            <th>Tanggal</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $no = ($inbox->currentPage() - 1) * $inbox->perPage() + 1;
                        @endphp
                        @forelse ($inbox as $item)
                            <tr>
                                <td><span class="text-muted">{{ $no++ }}</span></td>
                                <td>{{ $item->full_name }}</td>
                                <td>{{ $item->phone }}</td>
                                <td><span class="badge bg-blue-lt">{{ $item->category }}</span></td>
                                <td class="text-wrap" style="max-width: 300px;">{{ Str::limit($item->message, 100) }}
                                </td>
                                <td class="text-muted">{{ $item->created_at->format('d M Y H:i') }}</td>
                                <td>
                                    <form action="{{ route('inbox.destroy', $item->id) }}" method="POST"
                                        onsubmit="return confirm('Hapus pesan ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm btn-icon" title="Hapus">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24"
                                                height="24" viewBox="0 0 24 24" stroke-width="2"
                                                stroke="currentColor" fill="none" stroke-linecap="round"
                                                stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path d="M4 7l16 0" />
                                                <path d="M10 11l0 6" />
                                                <path d="M14 11l0 6" />
                                                <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                                                <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                                            </svg>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <x-backoffice.table.empty colspan="7" />
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer d-flex align-items-center">
                <div class="ms-auto m-0">
                    {{ $inbox->links('vendor.pagination.custom') }}
                </div>
            </div>
        </div>
    </div>
</x-backoffice.layout.main>
