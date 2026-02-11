<x-backoffice.layout.main>
    <x-backoffice.partials.breadcrumb
        title="Edit Visi/Misi"
        pretitle="Visi & Misi"
        createUrl="{{ route('vision-missions.index') }}"
        createLabel="Kembali"
    />

    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('vision-missions.update', $visionMission->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Tipe</label>
                        <select name="type" class="form-select @error('type') is-invalid @enderror">
                            <option value="">-- Pilih Tipe --</option>
                            <option value="vision" {{ old('type', $visionMission->type) === 'vision' ? 'selected' : '' }}>Visi</option>
                            <option value="mission" {{ old('type', $visionMission->type) === 'mission' ? 'selected' : '' }}>Misi</option>
                        </select>
                        @error('type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Konten</label>
                        <textarea
                            name="content"
                            rows="5"
                            class="form-control @error('content') is-invalid @enderror"
                            placeholder="Tuliskan isi visi atau misi di sini">{{ old('content', $visionMission->content) }}</textarea>
                        @error('content')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary">Update</button>
                        <a href="{{ route('vision-missions.index') }}" class="btn btn-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-backoffice.layout.main>


