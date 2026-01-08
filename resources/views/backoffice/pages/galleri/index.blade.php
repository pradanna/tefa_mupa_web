<x-backoffice.layout.main>
    <x-backoffice.partials.breadcrumb title="Galleri" pretitle="Galleri" createUrl="{{ route('album.create') }}" createLabel="Tambah Gambar" />
    <div class="page-body">
        <div class="container-xl">
            <div class="row row-cards">
                @foreach ( $gallerys as $gallery )
                    <div class="col-sm-3 col-lg-2">
                        <div class="card card-sm position-relative">
                            <!-- Tombol hapus -->
                            <form action="{{ route('album.destroy',$gallery) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-icon btn-danger btn-sm position-absolute" style="top: 8px; right: 8px; z-index: 2;" title="Hapus" onclick="return confirm('Anda yakin ingin menghapus gambar ini?')">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-trash" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <line x1="4" y1="7" x2="20" y2="7" />
                                        <line x1="10" y1="11" x2="10" y2="17" />
                                        <line x1="14" y1="11" x2="14" y2="17" />
                                        <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                                        <path d="M9 7V4a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                                    </svg>
                                </button>
                            </form>
                            <a href="#" class="d-block">
                                <img src="{{ asset($gallery->path . '/' . $gallery->image) }}" class="card-img-top" alt="Gallery Image">
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-backoffice.layout.main>
