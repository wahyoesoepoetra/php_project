@extends('layouts.admin-layout')

@section('content')
    <h2 class="mb-4 text-xl font-semibold text-gray-800">{{ $title }}</h2>

    @if (session('success'))
        <div class="mb-4 rounded border border-green-200 bg-green-50 p-3 text-green-800" role="status">{{ session('success') }}</div>
    @endif
    @if ($errors->any())
        <div class="mb-4 rounded border border-red-200 bg-red-50 p-3 text-red-800" role="alert">
            <ul class="list-inside list-disc">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <section class="rounded bg-white px-4 py-4 shadow sm:px-6">
        <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <a href="{{ route('petani.create') }}" class="inline-flex w-fit rounded border border-purple-200 px-4 py-2 text-sm font-semibold text-purple-700 hover:bg-purple-700 hover:text-white">Tambah</a>
            <form action="{{ route('petani.index') }}" method="GET" class="flex flex-col gap-2 sm:flex-row">
                <label class="sr-only" for="q">Cari petani</label>
                <input id="q" name="q" value="{{ request('q') }}" type="search" maxlength="100" placeholder="Cari nama, NIK, kontak, alamat..." class="min-w-0 rounded border-gray-300 sm:w-64">
                <label class="sr-only" for="kelompok">Kelompok tani</label>
                <select id="kelompok" name="kelompok" class="rounded border-gray-300">
                    <option value="">Semua kelompok</option>
                    @foreach ($kelompoks as $kelompok)
                        <option value="{{ $kelompok->id_kelompok_tani }}" @selected((string) request('kelompok') === (string) $kelompok->id_kelompok_tani)>{{ $kelompok->nama_kelompok }}</option>
                    @endforeach
                </select>
                <button type="submit" class="rounded bg-blue-600 px-4 py-2 text-white hover:bg-blue-700">Cari</button>
                @if (request()->filled('q') || request()->filled('kelompok'))
                    <a href="{{ route('petani.index') }}" class="rounded border px-4 py-2 text-center text-gray-700">Reset</a>
                @endif
            </form>
        </div>

        <div class="overflow-x-auto rounded border border-gray-200">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50"><tr>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Nama</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Kontak</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Kelompok</th>
                    <th class="px-4 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500">Aksi</th>
                </tr></thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    @forelse ($petanis as $item)
                        <tr>
                            <td class="px-4 py-4"><div class="flex items-center">
                                <div class="mr-3 h-10 w-10 flex-shrink-0">
                                    @if ($item->foto)<img class="h-10 w-10 rounded-full object-cover" src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($item->foto) }}" alt="Foto {{ $item->nama }}">@else<div class="flex h-10 w-10 items-center justify-center rounded-full bg-gray-100 text-gray-500">—</div>@endif
                                </div><div><div class="text-sm font-medium text-gray-900">{{ $item->nama }}</div><div class="text-sm text-gray-500">{{ $item->nik }}</div></div>
                            </div></td>
                            <td class="px-4 py-4 text-sm"><div class="text-gray-900">{{ $item->telp }}</div><div class="text-gray-500">{{ $item->alamat }}</div></td>
                            <td class="px-4 py-4 text-sm text-gray-500">{{ $item->kelompoktani?->nama_kelompok ?? 'Belum ada kelompok' }}</td>
                            <td class="whitespace-nowrap px-4 py-4 text-right text-sm"><a href="{{ route('petani.edit', $item->id_penjual) }}" class="mr-2 text-indigo-600 hover:text-indigo-900">Edit</a>
                                <form class="inline" action="{{ route('petani.destroy', $item->id_penjual) }}" method="POST" onsubmit="return confirm('Anda yakin ingin menghapus data ini?')">@csrf @method('DELETE')<button class="text-red-600 hover:text-red-900" type="submit">Del</button></form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-4 py-8 text-center text-sm text-gray-500">Tidak ada data petani yang sesuai.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $petanis->links() }}</div>
    </section>
@endsection
