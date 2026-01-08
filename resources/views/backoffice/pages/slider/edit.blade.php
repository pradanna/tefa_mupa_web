<x-backoffice.layout.main>
    <x-backoffice.partials.breadcrumb title="Slider" pretitle="Edit Slider" />
    <div class="col-12">
        <div class="card">
            {{-- <div class="card-header">
                <h3 class="card-title">Invoices</h3>
            </div> --}}
        </div>
        <div class="col-12">
            <form action="{{ route('sliders.update', $slider->id) }}" method="POST" class="card" enctype="multipart/form-data">
                @csrf @method('PUT')
                <div class="card-header">
                    <h4 class="card-title">Form Edit Slider</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="row">
                                <div class="col-md-6 col-xl-12">
                                    <div class="mb-3">
                                        <label class="form-label">Judul</label>
                                        <input type="text" class="form-control" name="title" value="{{ $slider->title }}"
                                            placeholder="Judul">
                                    </div>
                                    <div class="mb-3">
                                        <div class="form-label">Gambar</div>
                                        <input type="file" class="form-control" name="file" accept=".jpg,.jpeg,.png" id="file-input" />
                                    </div>
                                    <div class="mb-3" id="preview-wrapper">
                                        <label class="form-label">Preview Gambar Saat Ini</label>
                                        <div>
                                            <img
                                                id="image-preview"
                                                src="{{ isset($slider->path, $slider->file) ? rtrim($slider->path, '/') . '/' . $slider->file : '' }}"
                                                alt="Preview Gambar"
                                                class="img-fluid rounded"
                                                style="max-width: 300px; max-height: 200px;@if(!$slider->file) display:none;@endif"
                                            >
                                        </div>
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
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var fileInput = document.getElementById('file-input');
            var previewImg = document.getElementById('image-preview');
            var previewWrapper = document.getElementById('preview-wrapper');

            fileInput.addEventListener('change', function(e){
                if (e.target.files && e.target.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(ev){
                        previewImg.src = ev.target.result;
                        previewImg.style.display = 'block';
                        previewWrapper.style.display = '';
                    };
                    reader.readAsDataURL(e.target.files[0]);
                } else {
                    // No file selected, fall back to original or hide if none
                    @if(!$slider->file)
                        previewImg.src = '';
                        previewImg.style.display = 'none';
                        previewWrapper.style.display = 'none';
                    @else
                        previewImg.src = "{{ $slider->path }}";
                        previewImg.style.display = 'block';
                        previewWrapper.style.display = '';
                    @endif
                }
            });
        });
    </script>
</x-backoffice.layout.main>
