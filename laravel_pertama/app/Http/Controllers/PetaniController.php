<?php

namespace App\Http\Controllers;

use App\Models\KelompokTani;
use App\Models\Petani;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class PetaniController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
            'kelompok' => ['nullable', 'integer', Rule::exists('kelompok_tanis', 'id_kelompok_tani')],
        ], [
            'q.string' => 'Kata kunci harus berupa teks.',
            'q.max' => 'Kata kunci maksimal 100 karakter.',
            'kelompok.integer' => 'Kelompok tani tidak valid.',
            'kelompok.exists' => 'Kelompok tani yang dipilih tidak ditemukan.',
        ]);

        $petanis = Petani::with('kelompoktani')
            ->when($filters['q'] ?? null, function ($query, $keyword) {
                $query->where(function ($query) use ($keyword) {
                    foreach (['nama', 'nik', 'telp', 'alamat'] as $column) {
                        $query->orWhere($column, 'like', "%{$keyword}%");
                    }
                    $query->orWhereHas('kelompoktani', fn ($group) => $group->where('nama_kelompok', 'like', "%{$keyword}%"));
                });
            })
            ->when($filters['kelompok'] ?? null, fn ($query, $groupId) => $query->where('id_kelompok_tani', $groupId))
            ->orderBy('nama')
            ->paginate(10)
            ->withQueryString();

        return view('admin.daftarpetani', [
            'title' => 'Daftar Petani',
            'petanis' => $petanis,
            'kelompoks' => KelompokTani::orderBy('nama_kelompok')->get(),
        ]);
    }

    public function create()
    {
        return view('admin.inputpetani', ['title' => 'Tambah Petani', 'petani' => new Petani(), 'kelompoks' => KelompokTani::orderBy('nama_kelompok')->get()]);
    }

    public function store(Request $request)
    {
        $data = $this->validatedData($request);
        $data['foto'] = $request->file('foto')->store('petani', 'public');
        Petani::create($data);

        return redirect()->route('petani.index')->with('success', 'Data petani berhasil ditambahkan.');
    }

    public function show(Petani $petani)
    {
        return redirect()->route('petani.edit', $petani->id_penjual);
    }

    public function edit(Petani $petani)
    {
        return view('admin.inputpetani', ['title' => 'Edit Petani', 'petani' => $petani, 'kelompoks' => KelompokTani::orderBy('nama_kelompok')->get()]);
    }

    public function update(Request $request, Petani $petani)
    {
        $data = $this->validatedData($request, $petani);
        if ($request->hasFile('foto')) {
            $newPhoto = $request->file('foto')->store('petani', 'public');
            $oldPhoto = $petani->foto;
            $data['foto'] = $newPhoto;
            $petani->update($data);
            if ($oldPhoto && Storage::disk('public')->exists($oldPhoto)) {
                Storage::disk('public')->delete($oldPhoto);
            }
        } else {
            unset($data['foto']);
            $petani->update($data);
        }

        return redirect()->route('petani.index')->with('success', 'Data petani berhasil diperbarui.');
    }

    public function destroy(Petani $petani)
    {
        $petani->delete();
        return redirect()->route('petani.index')->with('success', 'Data petani berhasil dihapus.');
    }

    private function validatedData(Request $request, ?Petani $petani = null): array
    {
        return $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'nik' => ['required', 'string', 'size:16', Rule::unique('petanis', 'nik')->ignore($petani?->id_penjual, 'id_penjual')],
            'alamat' => ['required', 'string', 'max:100'],
            'telp' => ['required', 'string', 'max:255'],
            'id_kelompok_tani' => ['required', 'integer', Rule::exists('kelompok_tanis', 'id_kelompok_tani')],
            'foto' => [$petani ? 'nullable' : 'required', 'image', 'max:2048'],
            'status' => ['required', 'in:Y,N'],
        ], [
            'required' => ':attribute wajib diisi.', 'string' => ':attribute harus berupa teks.',
            'nama.max' => 'Nama maksimal 255 karakter.', 'nik.size' => 'NIK harus tepat 16 karakter.',
            'nik.unique' => 'NIK sudah terdaftar.', 'alamat.max' => 'Alamat maksimal 100 karakter.',
            'id_kelompok_tani.exists' => 'Kelompok tani yang dipilih tidak ditemukan.',
            'foto.required' => 'Foto wajib diunggah.', 'foto.image' => 'File harus berupa gambar.',
            'foto.max' => 'Ukuran foto maksimal 2 MB.', 'status.in' => 'Status harus Aktif (Y) atau Non-Aktif (N).',
        ], ['nama' => 'nama', 'nik' => 'NIK', 'alamat' => 'alamat', 'telp' => 'nomor telepon', 'id_kelompok_tani' => 'kelompok tani', 'foto' => 'foto', 'status' => 'status']);
    }
}
