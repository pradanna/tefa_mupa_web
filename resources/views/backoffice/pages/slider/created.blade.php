<x-backoffice.layout.main>
    <x-backoffice.partials.breadcrumb title="Slider" pretitle="Tambah Slider" />
    <div class="col-12">
        <div class="card">
            {{-- <div class="card-header">
                <h3 class="card-title">Invoices</h3>
            </div> --}}
        </div>
        <div class="col-12">
            <form action="{{ route('sliders.store') }}" method="POST" class="card" enctype="multipart/form-data">
                @csrf
                <div class="card-header">
                    <h4 class="card-title">Form Tambah Slider</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="row">
                                <div class="col-md-6 col-xl-12">
                                    <div class="mb-3">
                                        <label class="form-label">Judul</label>
                                        <input type="text" class="form-control" name="title"
                                            placeholder="Judul">
                                    </div>
                                    <div class="mb-3">
                                        <div class="form-label">Gambar</div>
                                        <input type="file" class="form-control" name="file" accept=".jpg,.jpeg,.png" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-end">
                    <div class="d-flex">
                        <a href="{{ route('sliders.index') }}" class="btn btn-link">Batal</a>
                        <button type="submit" class="btn btn-primary ms-auto">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-backoffice.layout.main>
