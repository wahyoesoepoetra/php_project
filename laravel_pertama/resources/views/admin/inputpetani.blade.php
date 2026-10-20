@extends('layouts.admin-layout')

@section('content')
    <h2 class="mb-4 text-xl font-semibold text-gray-800">{{ $title }}</h2>
    <section class="max-w-3xl rounded bg-white p-6 shadow">
        @if ($errors->any())
            <div class="mb-4 rounded border border-red-200 bg-red-50 p-3 text-red-800" role="alert">
                <ul class="list-inside list-disc">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif
        <form action="{{ $petani->exists ? route('petani.update', $petani->id_penjual) : route('petani.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @if ($petani->exists) @method('PUT') @endif
            <div><label for="nama" class="mb-1 block text-sm font-medium">Nama</label><input id="nama" name="nama" value="{{ old('nama', $petani->nama) }}" required maxlength="255" class="w-full rounded border-gray-300">@error('nama')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror</div>
            <div><label for="nik" class="mb-1 block text-sm font-medium">NIK</label><input id="nik" name="nik" value="{{ old('nik', $petani->nik) }}" required minlength="16" maxlength="16" class="w-full rounded border-gray-300">@error('nik')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror</div>
            <div><label for="telp" class="mb-1 block text-sm font-medium">Nomor telepon</label><input id="telp" name="telp" value="{{ old('telp', $petani->telp) }}" required maxlength="255" class="w-full rounded border-gray-300">@error('telp')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror</div>
            <div><label for="alamat" class="mb-1 block text-sm font-medium">Alamat</label><textarea id="alamat" name="alamat" required maxlength="100" rows="3" class="w-full rounded border-gray-300">{{ old('alamat', $petani->alamat) }}</textarea>@error('alamat')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror</div>
            <div><label for="id_kelompok_tani" class="mb-1 block text-sm font-medium">Kelompok tani</label><select id="id_kelompok_tani" name="id_kelompok_tani" required class="w-full rounded border-gray-300"><option value="">Pilih kelompok tani</option>@foreach ($kelompoks as $kelompok)<option value="{{ $kelompok->id_kelompok_tani }}" @selected((string) old('id_kelompok_tani', $petani->id_kelompok_tani) === (string) $kelompok->id_kelompok_tani)>{{ $kelompok->nama_kelompok }}</option>@endforeach</select>@error('id_kelompok_tani')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror</div>
            <div><label for="foto" class="mb-1 block text-sm font-medium">Foto {{ $petani->exists ? '(opsional)' : '' }}</label><input id="foto" name="foto" type="file" accept="image/*" {{ $petani->exists ? '' : 'required' }} class="w-full rounded border border-gray-300 p-2">@if ($petani->foto)<p class="mt-1 text-sm text-gray-500">Foto saat ini: {{ basename($petani->foto) }}</p>@endif @error('foto')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror</div>
            <fieldset>
                <legend class="mb-1 block text-sm font-medium">Status</legend>
                <div class="flex flex-wrap gap-4">
                    <label class="inline-flex items-center gap-2"><input type="radio" name="status" value="Y" required @checked(old('status', $petani->status) === 'Y')> Aktif</label>
                    <label class="inline-flex items-center gap-2"><input type="radio" name="status" value="N" required @checked(old('status', $petani->status) === 'N')> Non-Aktif</label>
                </div>
                @error('status')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </fieldset>
            <div class="flex gap-2 pt-2"><button type="submit" class="rounded bg-blue-600 px-4 py-2 font-semibold text-white hover:bg-blue-700">Simpan</button><a href="{{ route('petani.index') }}" class="rounded border px-4 py-2 text-gray-700">Batal</a></div>
        </form>
    </section>
@endsection
