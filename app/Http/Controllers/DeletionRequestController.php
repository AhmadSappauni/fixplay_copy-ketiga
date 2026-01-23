<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DeletionRequest;
use App\Models\Sale;
use App\Models\Product;
use App\Models\GameSession; // <--- [PENTING] Tambahkan Model Ini
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DeletionRequestController extends Controller
{
    // 1. Karyawan Mengirim Request Hapus
    public function store(Request $request)
    {
        $request->validate([
            'target_table' => 'required|in:sales,products',
            'target_id'    => 'required|integer',
            'reason'       => 'required|string|max:255',
        ]);

        // Ambil deskripsi singkat data agar Boss tau apa yg dihapus
        $desc = '';
        if ($request->target_table == 'sales') {
            $sale = Sale::find($request->target_id);
            if ($sale) {
                // Buat ringkasan item
                $items = $sale->items->take(2)->map(function($i){
                    return $i->product ? $i->product->name : $i->description;
                })->implode(', ');
                $desc = "Transaksi #{$sale->id} - {$items} (Rp " . number_format($sale->total) . ")";
            }
        } elseif ($request->target_table == 'products') {
            $prod = Product::find($request->target_id);
            if ($prod) {
                $desc = "Produk: {$prod->name} (Stok: {$prod->stock})";
            }
        }

        DeletionRequest::create([
            'user_id'      => Auth::id(),
            'target_table' => $request->target_table,
            'target_id'    => $request->target_id,
            'description'  => $desc,
            'reason'       => $request->reason,
            'status'       => 'pending',
        ]);

        return back()->with('success', 'Permintaan hapus telah dikirim ke Boss.');
    }

    // 2. Boss Eksekusi Request (Approve/Reject)
    public function handle(Request $request, $id)
    {
        // Pastikan hanya Boss yg bisa akses
        if (Auth::user()->role !== 'boss') {
            return abort(403);
        }

        $req = DeletionRequest::findOrFail($id);
        $action = $request->input('action'); // 'approve' atau 'reject'

        if ($action === 'reject') {
            $req->update(['status' => 'rejected']);
            return back()->with('success', 'Permintaan hapus ditolak.');
        }

        if ($action === 'approve') {
            try {
                DB::transaction(function () use ($req) {
                    
                    // === LOGIKA HAPUS DATA SALES (KEUANGAN & SESI) ===
                    if ($req->target_table == 'sales') {
                        $sale = Sale::find($req->target_id);
                        
                        if ($sale) {
                            // [PERBAIKAN UTAMA DISINI]
                            // Cari Sesi yang terhubung dengan Transaksi ini
                            $session = GameSession::where('sale_id', $sale->id)->first();
                            
                            // Jika ada sesinya, HAPUS JUGA SESINYA
                            if ($session) {
                                $session->delete();
                            }

                            // Baru hapus uangnya
                            $sale->delete(); 
                        }
                    } 
                    // === LOGIKA HAPUS DATA PRODUK ===
                    elseif ($req->target_table == 'products') {
                        $prod = Product::find($req->target_id);
                        if ($prod) $prod->delete(); 
                    }
                    
                    // Update status request jadi approved
                    $req->update(['status' => 'approved']);
                });
                
                return back()->with('success', 'Data berhasil dihapus (Termasuk Riwayat Sesi).');
            } catch (\Exception $e) {
                return back()->with('error', 'Gagal menghapus data: ' . $e->getMessage());
            }
        }
        
        return back();
    }
}