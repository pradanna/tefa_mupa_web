<x-backoffice.layout.main>
    <x-backoffice.partials.breadcrumb title="Galleri" pretitle="Tambah Galleri" />
    <x-backoffice.partials.head>
        <style>
            .dropzone .dz-progress {
                display: none !important;
            }
        </style>
    </x-backoffice.partials.head>
    <div class="col-12">
        <div class="card">

            <div class="card-header">
                <h4 class="card-title">Form Tambah Galleri</h4>
            </div>

            <div class="card-body">
                <form class="dropzone" id="dropzone-custom" action="{{ route('api.save-image') }}" autocomplete="off" novalidate>
                    <div class="fallback">
                        <input name="file" type="file" />
                    </div>
                    <div class="dz-message">
                        <h3 class="dropzone-msg-title">Your text here</h3>
                        <span class="dropzone-msg-desc">Your custom description here</span>
                    </div>
                </form>


            </div>

            <div class="card-footer text-end">
                <a href="{{ route('album.index') }}" class="btn btn-link">Kembali</a>
            </div>

        </div>
    </div>
    <x-backoffice.partials.scripts>
        <script>
            Dropzone.autoDiscover = false;
            var myDropzone = new Dropzone("#dropzone-custom", {
                autoProcessQueue: true,
                paramName: "file",
                maxFilesize: 5, // MB
                acceptedFiles: 'image/*',
                addRemoveLinks: true,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            document.getElementById('submitGallery').addEventListener('click', function(e) {
                e.preventDefault();
                myDropzone.processQueue();
            });
        </script>
    </x-backoffice.partials.scripts>
</x-backoffice.layout.main>
