<x-backoffice.layout.main>
    <x-backoffice.partials.breadcrumb title="Slider" pretitle="Slider" createUrl="{{ route('sliders.create') }}"
        createLabel="Tambah Slider" />
    <div class="col-12">
        <div class="card">
            <div class="card-body border-bottom py-3">
                <form method="GET" action="{{ url()->current() }}">
                    <div class="d-flex">
                        <div class="text-muted">
                            Show
                            <div class="mx-2 d-inline-block">
                                <select name="limit" class="form-control form-control-sm" aria-label="Slider count">
                                    @foreach ([5, 10, 25, 50] as $value)
                                        <option value="{{ $value }}"
                                            {{ (int) request('limit', $sliders->perPage()) === $value ? 'selected' : '' }}>
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
                                    class="form-control form-control-sm" aria-label="Search slider">
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="search_column" value="title">
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
                            <th>Title</th>
                            <th>Subtitle</th>
                            <th>Image</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $no = 1;
                        @endphp
                        @php
                            // Ambil nomor pertama pada halaman ini (untuk penomoran tabel yang konsisten dengan pagination)
                            $no = ($sliders->currentPage() - 1) * $sliders->perPage() + 1;
                        @endphp
                        @forelse ($sliders as $slider)
                            <tr>
                                <td><span class="text-muted">{{ $no++ }}</span></td>
                                <td class="text-muted">{{ $slider->title ?? '-' }}</td>
                                <td class="text-muted">{{ $slider->subtitle ?? '-' }}</td>
                                <td class="text-muted">
                                    @if ($slider->file)
                                        <img src="{{ $slider->path . '/' . $slider->file }}"
                                            alt="{{ $slider->title ?? 'Slider Image' }}"
                                            style="max-width: 120px; max-height: 80px; object-fit:cover; display:block; border-radius: 5px; border: 1px solid #eee;">
                                    @endif
                                </td>
                                <td class="text-muted">
                                    <span class="dropdown">
                                        <button class="btn dropdown-toggle align-text-top" data-bs-toggle="dropdown"
                                            data-bs-boundary="viewport"
                                            data-bs-popper-config='{"strategy": "fixed"}'>Actions</button>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            <a class="dropdown-item"
                                                href="{{ route('sliders.edit', $slider) }}">Edit</a>
                                            <form action="{{ route('sliders.destroy', $slider->id) }}" method="POST"
                                                style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item"
                                                    onclick="return confirm('Are you sure you want to delete this slider?')">Delete</button>
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
                        {{ ($sliders->currentPage() - 1) * $sliders->perPage() + 1 }}
                    </span>
                    to
                    <span>
                        {{ ($sliders->currentPage() - 1) * $sliders->perPage() + $sliders->count() }}
                    </span>
                    of
                    <span>
                        {{ $sliders->total() }}
                    </span>
                    entries
                </p>
                <div class="ms-auto m-0">
                    {{ $sliders->links('vendor.pagination.custom') }}
                </div>
            </div>
        </div>
    </div>
</x-backoffice.layout.main>
