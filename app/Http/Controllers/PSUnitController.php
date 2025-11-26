<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PSUnit;
use Illuminate\Database\QueryException; // Import library error database

class PSUnitController extends Controller
{
    public function index()
    {
        $units = PSUnit::orderBy('name')->get();
        return view('ps_units', compact('units'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:191',
            'type'        => 'required|string|max:50',
            'hourly_rate' => 'required|numeric|min:0',
            'is_active'   => 'nullable|boolean',
        ]);
        
        $data['is_active'] = $request->has('is_active') ? 1 : 0;
        $data['type'] = strtoupper($data['type']);

        PSUnit::create($data);
        return redirect()->route('ps_units.index')->with('success', 'Unit berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $unit = PSUnit::findOrFail($id);
        
        $data = $request->validate([
            'name'        => 'required|string|max:191',
            'type'        => 'required|string|max:50',
            'hourly_rate' => 'required|numeric|min:0',
        ]);

        $data['type'] = strtoupper($data['type']);

        $unit->update($data);
        return redirect()->route('ps_units.index')->with('success', 'Unit berhasil diperbarui.');
    }

    public function toggle($id)
    {
        $unit = PSUnit::findOrFail($id);
        $unit->is_active = !$unit->is_active;
        $unit->save();
        return redirect()->route('ps_units.index')->with('success', 'Status unit diperbarui.');
    }

    public function destroy($id)
    {
        try {
            $unit = PSUnit::findOrFail($id);
            $unit->delete();
            return redirect()->route('ps_units.index')->with('success', 'Unit dihapus.');
        } catch (QueryException $e) {
            // Error 1451: Cannot delete parent row (Foreign Key Constraint)
            if ($e->errorInfo[1] == 1451) {
                return redirect()->route('ps_units.index')->with('error', 'Gagal menghapus: Unit ini memiliki riwayat transaksi sewa. Silakan nonaktifkan saja statusnya.');
            }
            // Jika error lain, biarkan aplikasi menangani defaultnya
            throw $e;
        }
    }
}