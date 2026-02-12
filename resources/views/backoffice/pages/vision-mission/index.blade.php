<x-backoffice.layout.main>
    <x-backoffice.partials.breadcrumb title="Visi & Misi" pretitle="Visi & Misi"
        createUrl="{{ route('vision-missions.create') }}" createLabel="Tambah Visi/Misi" />

    <div class="col-12">
        <div class="card">
            <div class="card-body border-bottom py-3">
                <form method="GET" action="{{ url()->current() }}">
                    <div class="d-flex">
                        <div class="text-muted">
                            Show
                            <div class="mx-2 d-inline-block">
                                <select name="limit" class="form-control form-control-sm"
                                    aria-label="Vision Mission count">
                                    @php $collection = $visionMissions instanceof \Illuminate\Pagination\LengthAwarePaginator ? $visionMissions : null; @endphp
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
                            Search:
                            <div class="ms-2 d-inline-block">
                                <input type="text" name="search" value="{{ request('search') }}"
                                    class="form-control form-control-sm" aria-label="Search vision mission">
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="search_column" value="content">
                </form>
            </div>

            <div class="table-responsive" style="position: relative;">
                <table class="table card-table table-vcenter  datatable">
                    <thead>
                        <tr>
                            <th class="w-1">No.</th>
                            <th>Tipe</th>
                            <th>Konten</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            if ($visionMissions instanceof \Illuminate\Pagination\LengthAwarePaginator) {
                                $no = ($visionMissions->currentPage() - 1) * $visionMissions->perPage() + 1;
                            } else {
                                $no = 1;
                            }
                        @endphp
                        @forelse ($visionMissions as $vm)
                            <tr>
                                <td><span class="text-muted">{{ $no++ }}</span></td>
                                <td class="text-muted text-uppercase">{{ $vm->type ?? '-' }}</td>
                                <td class="text-muted">{{ \Illuminate\Support\Str::limit($vm->content ?? '-', 80) }}
                                </td>
                                <td class="text-muted">
                                    <span class="dropdown">
                                        <button class="btn dropdown-toggle align-text-top" data-bs-toggle="dropdown"
                                            data-bs-boundary="viewport"
                                            data-bs-popper-config='{"strategy": "fixed"}'>Actions</button>
                                        <div class="dropdown-menu dropdown-menu-start"
                                            style="margin-top: 30px; z-index: 9999; position: absolute;">
                                            <a class="dropdown-item"
                                                href="{{ route('vision-missions.edit', $vm->id) }}">Edit</a>
                                            <form action="{{ route('vision-missions.destroy', $vm->id) }}"
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
                            <x-backoffice.table.empty colspan="4" />
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($visionMissions instanceof \Illuminate\Pagination\LengthAwarePaginator)
                <div class="card-footer d-flex align-items-center">
                    <p class="m-0 text-muted">
                        Showing
                        <span>
                            {{ ($visionMissions->currentPage() - 1) * $visionMissions->perPage() + 1 }}
                        </span>
                        to
                        <span>
                            {{ ($visionMissions->currentPage() - 1) * $visionMissions->perPage() + $visionMissions->count() }}
                        </span>
                        of
                        <span>
                            {{ $visionMissions->total() }}
                        </span>
                        entries
                    </p>
                    <div class="ms-auto m-0">
                        {{ $visionMissions->links('vendor.pagination.custom') }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-backoffice.layout.main>
