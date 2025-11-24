<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class KaryawanController extends Controller
{
    /**
     * Tampilkan daftar karyawan.
     */
    public function index()
    {
        $karyawans = Karyawan::latest()->get();

        return view('karyawan.index', compact('karyawans'));
    }

    /**
     * Form tambah karyawan.
     */
    public function create()
    {
        return view('karyawan.create');
    }

    /**
     * Simpan karyawan baru.
     */
    public function store(Request $request)
    {
        // Tidak pakai validasi ribet, sesuai permintaanmu 🙂
        $data = $request->only(['nama', 'phone']);

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('karyawans', 'public');
        }

        Karyawan::create($data);

        return redirect()->route('karyawan.index');
    }

    /**
     * Form edit karyawan.
     */
    public function edit(Karyawan $karyawan)
    {
        return view('karyawan.edit', compact('karyawan'));
    }

    /**
     * Update data karyawan.
     */
    public function update(Request $request, Karyawan $karyawan)
    {
        $data = $request->only(['nama', 'phone']);

        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($karyawan->foto && Storage::disk('public')->exists($karyawan->foto)) {
                Storage::disk('public')->delete($karyawan->foto);
            }

            $data['foto'] = $request->file('foto')->store('karyawans', 'public');
        }

        $karyawan->update($data);

        return redirect()->route('karyawan.index');
    }

    /**
     * Hapus karyawan (hapus beneran + fotonya).
     */
    public function destroy(Karyawan $karyawan)
    {
        if ($karyawan->foto && Storage::disk('public')->exists($karyawan->foto)) {
            Storage::disk('public')->delete($karyawan->foto);
        }

        $karyawan->delete();

        return redirect()->route('karyawan.index');
    }
}
