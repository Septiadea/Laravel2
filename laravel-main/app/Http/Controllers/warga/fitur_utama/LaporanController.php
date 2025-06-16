<?php

namespace App\Http\Controllers\warga\fitur_utama;

use App\Http\Controllers\Controller;
use App\Models\Laporan;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\Rw;
use App\Models\Rt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class LaporanController extends Controller
{
    public function index()
    {
        // Get all kecamatan for dropdown
        $kecamatans = Kecamatan::orderBy('nama_kecamatan')->get();
        
        // Initialize empty collections for dropdowns
        $kelurahans = collect();
        $rws = collect();
        $rts = collect();
        
        // Get laporan history for the current user
        $laporans = Laporan::where('warga_id', auth('warga')->id())
                      ->with(['kecamatan', 'kelurahan', 'rw', 'rt', 'warga'])
                      ->orderBy('created_at', 'desc')
                      ->paginate(10);
    
        return view('warga.laporan', compact('kecamatans', 'kelurahans', 'rws', 'rts', 'laporans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'jenis_laporan' => 'required|string|in:Jentik Nyamuk,Kasus DBD,Lingkungan Kotor',
            'kecamatan_id' => 'required|exists:kecamatans,id',
            'kelurahan_id' => 'required|exists:kelurahans,id',
            'rw_id' => 'required|exists:rws,id',
            'rt_id' => 'required|exists:rts,id',
            'alamat_detail' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'foto_pelaporan' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        try {
            // Handle file upload
            $fotoPath = null;
            if ($request->hasFile('foto_pelaporan')) {
                $file = $request->file('foto_pelaporan');
                $fileName = Str::uuid() . '.' . $file->getClientOriginalExtension();
                
                // Ensure directory exists
                $uploadPath = public_path('images/laporan');
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }
                
                $file->move($uploadPath, $fileName);
                $fotoPath = $fileName;
            }

            // Determine hasil based on jenis laporan
            $hasil = $this->determineHasil($request->jenis_laporan);

            // Create new laporan
            $laporan = Laporan::create([
                'warga_id' => auth('warga')->id(),
                'jenis_laporan' => $request->jenis_laporan,
                'kecamatan_id' => $request->kecamatan_id,
                'kelurahan_id' => $request->kelurahan_id,
                'rw_id' => $request->rw_id,
                'rt_id' => $request->rt_id,
                'alamat_detail' => $request->alamat_detail,
                'deskripsi' => $request->deskripsi,
                'foto_bukti' => $fotoPath,
                'hasil' => $hasil,
                'status' => 'Diproses',
            ]);

            return redirect()->route('laporan.index')->with('success', 'Laporan berhasil dikirim!');

        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Determine hasil based on jenis laporan
     */
    private function determineHasil($jenisLaporan)
    {
        switch ($jenisLaporan) {
            case 'Jentik Nyamuk':
                return 'Bahaya';
            case 'Kasus DBD':
                return 'Bahaya';
            case 'Lingkungan Kotor':
                return 'Perlu Perhatian';
            default:
                return 'Aman';
        }
    }

    /**
     * Show detail laporan
     */
    public function show($id)
    {
        try {
            $laporan = Laporan::with(['kecamatan', 'kelurahan', 'rw', 'rt', 'warga'])
                             ->where('warga_id', auth('warga')->id())
                             ->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $laporan
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }
    }

    // AJAX endpoints for hierarchical dropdowns - DIPERBAIKI
// AJAX endpoints for hierarchical dropdowns - DIPERBAIKI
public function getKelurahan(Request $request)
{
    // Validasi input
    $request->validate([
        'kecamatan_id' => 'required|exists:kecamatans,id'
    ]);

    $kecamatan_id = $request->kecamatan_id;
    
    try {
        $kelurahans = Kelurahan::where('kecamatan_id', $kecamatan_id)
                              ->orderBy('nama_kelurahan', 'asc')
                              ->get();
        
        $options = '<option value="">Pilih Kelurahan</option>';
        foreach ($kelurahans as $kelurahan) {
            $selected = (old('kelurahan_id') == $kelurahan->id) ? 'selected' : '';
            $options .= "<option value='{$kelurahan->id}' {$selected}>{$kelurahan->nama_kelurahan}</option>";
        }
        
        return response($options);
        
    } catch (\Exception $e) {
        return response('<option value="">Error loading data</option>', 500);
    }
}

public function getRw(Request $request)
{
    // Validasi input
    $request->validate([
        'kelurahan_id' => 'required|exists:kelurahans,id'
    ]);

    $kelurahan_id = $request->kelurahan_id;
    
    try {
        $rws = Rw::where('kelurahan_id', $kelurahan_id)
                 ->orderBy('nomor_rw', 'asc')
                 ->get();
        
        $options = '<option value="">Pilih RW</option>';
        foreach ($rws as $rw) {
            $selected = (old('rw_id') == $rw->id) ? 'selected' : '';
            $options .= "<option value='{$rw->id}' {$selected}>RW {$rw->nomor_rw}</option>";
        }
        
        return response($options);
        
    } catch (\Exception $e) {
        return response('<option value="">Error loading data</option>', 500);
    }
}

public function getRt(Request $request)
{
    // Validasi input
    $request->validate([
        'rw_id' => 'required|exists:rws,id'
    ]);

    $rw_id = $request->rw_id;
    
    try {
        $rts = Rt::where('rw_id', $rw_id)
                 ->orderBy('nomor_rt', 'asc')
                 ->get();
        
        $options = '<option value="">Pilih RT</option>';
        foreach ($rts as $rt) {
            $selected = (old('rt_id') == $rt->id) ? 'selected' : '';
            $options .= "<option value='{$rt->id}' {$selected}>RT {$rt->nomor_rt}</option>";
        }
        
        return response($options);
        
    } catch (\Exception $e) {
        return response('<option value="">Error loading data</option>', 500);
    }
}

}