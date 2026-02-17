<x-backoffice.layout.main>
    <x-backoffice.partials.breadcrumb
        title="Tambah Kontak"
        pretitle="Kontak"
        createUrl="{{ route('contacts.index') }}"
        createLabel="Kembali"
    />

    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('contacts.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Alamat Sekolah</label>
                        <textarea
                            name="address"
                            rows="3"
                            class="form-control @error('address') is-invalid @enderror"
                            placeholder="Masukkan alamat lengkap sekolah">{{ old('address') }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    value="{{ old('email') }}" placeholder="contoh: sekolah@example.com">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Telepon / WhatsApp</label>
                                <input type="text" name="phone"
                                    class="form-control @error('phone') is-invalid @enderror"
                                    value="{{ old('phone') }}" placeholder="contoh: 08xx-xxxx-xxxx">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Jam Operasional (Senin - Jumat)</label>
                                <input type="text" name="weekday_hours"
                                    class="form-control @error('weekday_hours') is-invalid @enderror"
                                    value="{{ old('weekday_hours') }}" placeholder="contoh: 07.30 - 16.00 WIB">
                                @error('weekday_hours')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Jam Operasional (Sabtu / Minggu)</label>
                                <input type="text" name="saturday_hours"
                                    class="form-control @error('saturday_hours') is-invalid @enderror"
                                    value="{{ old('saturday_hours') }}" placeholder="contoh: Tutup">
                                @error('saturday_hours')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <hr>
                    <h4 class="mb-3">Media Sosial</h4>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="form-label">Facebook URL</label>
                                <input type="text" name="facebook_url"
                                    class="form-control @error('facebook_url') is-invalid @enderror"
                                    value="{{ old('facebook_url') }}" placeholder="https://facebook.com/...">
                                @error('facebook_url')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="form-label">Instagram URL</label>
                                <input type="text" name="instagram_url"
                                    class="form-control @error('instagram_url') is-invalid @enderror"
                                    value="{{ old('instagram_url') }}" placeholder="https://instagram.com/...">
                                @error('instagram_url')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="form-label">TikTok URL</label>
                                <input type="text" name="tiktok_url"
                                    class="form-control @error('tiktok_url') is-invalid @enderror"
                                    value="{{ old('tiktok_url') }}" placeholder="https://tiktok.com/@...">
                                @error('tiktok_url')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="form-label">YouTube URL</label>
                                <input type="text" name="youtube_url"
                                    class="form-control @error('youtube_url') is-invalid @enderror"
                                    value="{{ old('youtube_url') }}" placeholder="https://youtube.com/...">
                                @error('youtube_url')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select @error('status') is-invalid @enderror">
                            <option value="publis" {{ old('status', 'publis') === 'publis' ? 'selected' : '' }}>
                                Publis
                            </option>
                            <option value="unpublis" {{ old('status') === 'unpublis' ? 'selected' : '' }}>
                                Unpublis
                            </option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="{{ route('contacts.index') }}" class="btn btn-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-backoffice.layout.main>


