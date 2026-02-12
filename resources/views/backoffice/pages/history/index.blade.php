<x-backoffice.layout.main>
    <x-backoffice.partials.breadcrumb title="Profile / Sejarah" pretitle="Profile / Sejarah" />
    <div class="col-12">
        <div class="card">
            <div class="row row-cards">
                <div class="col-12">
                    @php
                        // Determine if we are creating a new record or updating an existing one.
                        $isUpdate = isset($history) && $history->id;
                        // Set the form action route accordingly.
                        $formAction = $isUpdate ? route('history.update', $history->id) : route('history.store');
                    @endphp
                    <form class="card" method="POST" action="{{ $formAction }}" enctype="multipart/form-data">
                        @csrf
                        @if ($isUpdate)
                            @method('PUT')
                        @endif
                        <div class="card-body">
                            <h3 class="card-title">{{ $isUpdate ? 'Edit' : 'Tambah' }} Profile/Sejarah</h3>
                            <div class="row row-cards">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="form-label">Judul</label>
                                        <input type="text" class="form-control" name="title" placeholder="title"
                                            value="{{ old('title', $history->title ?? '') }}">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label" for="image">Gambar</label>
                                    <input type="file" class="form-control" name="file" id="image"
                                        accept="image/*" />
                                </div>
                                @if ($isUpdate && !empty($history->image) && $history->image !== '-')
                                    <div class="mb-3">
                                        <label class="form-label">Current Image</label>
                                        <br>
                                        <img src="{{ $history->path . '/' . $history->image }}" alt="History Image"
                                            style="max-width: 200px;">
                                    </div>
                                @endif
                                <div class="col-md-12">
                                    <div class="mb-3 ">
                                        <label class="form-label">Body</label>
                                        <textarea rows="5" class="form-control" placeholder="Here can be your description" name="body">{!! old('body', $history->body ?? '') !!}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-end">
                            <button type="submit" class="btn btn-primary">{{ $isUpdate ? 'Update' : 'Simpan' }}
                                Profile</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-backoffice.layout.main>
