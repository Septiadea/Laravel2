@extends('layouts.warga')

@section('title', 'Laporan Warga - DengueCare')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8">
        <h1 class="text-3xl font-bold text-[#1D3557]">Laporan Warga</h1>
        <div class="flex items-center space-x-2 mt-4 md:mt-0">
            <span class="text-sm text-gray-600">Tanggal Hari Ini:</span>
            <span class="font-medium text-gray-800">{{ date('d F Y') }}</span>
        </div>
    </div>

    <!-- Info Cards -->
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl shadow-lg p-6 border border-blue-100 mb-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white p-4 rounded-lg shadow-sm border-l-4 border-blue-500">
                <div class="flex items-center">
                    <div class="p-2 rounded-full bg-blue-100 text-blue-600 mr-3">
                        <i class="fas fa-exclamation-circle fa-sm"></i>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-500 mb-1">Total Laporan</p>
                        <p class="text-lg font-medium text-gray-800">
                            {{ $laporans->total() }} Laporan
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white p-4 rounded-lg shadow-sm border-l-4 border-blue-500">
                <div class="flex items-center">
                    <div class="p-2 rounded-full bg-blue-100 text-blue-600 mr-3">
                        <i class="fas fa-check-circle fa-sm"></i>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-500 mb-1">Laporan Diterima</p>
                        <p class="text-lg font-medium text-gray-800">
                            {{ $laporans->where('status', 'Diterima')->count() }} Laporan
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white p-4 rounded-lg shadow-sm border-l-4 border-blue-500">
                <div class="flex items-center">
                    <div class="p-2 rounded-full bg-blue-100 text-blue-600 mr-3">
                        <i class="fas fa-clock fa-sm"></i>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-500 mb-1">Dalam Proses</p>
                        <p class="text-lg font-medium text-gray-800">
                            {{ $laporans->where('status', 'Diproses')->count() }} Laporan
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Laporan -->
    <form id="reportForm" action="{{ route('laporan.store') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-lg shadow-lg p-6 space-y-6">
        @csrf
        
        <!-- Jenis Laporan -->
        <div class="space-y-2">
            <label class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                <i class="fas fa-tag text-gray-500 mr-2 text-sm"></i>
                Jenis Laporan <span class="text-red-500">*</span>
            </label>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="report-type-option rounded-lg p-4 text-center cursor-pointer border-2 border-gray-200 transition-colors" 
                     data-value="Jentik Nyamuk">
                    <div class="bg-blue-100 p-3 rounded-full inline-block mb-2">
                        <i class="fas fa-bug text-blue-600"></i>
                    </div>
                    <h3 class="font-medium text-gray-800">Jentik Nyamuk</h3>
                    <p class="text-sm text-gray-500 mt-1">Temuan jentik nyamuk di lingkungan</p>
                </div>
                
                <div class="report-type-option rounded-lg p-4 text-center cursor-pointer border-2 border-gray-200 transition-colors" 
                     data-value="Kasus DBD">
                    <div class="bg-red-100 p-3 rounded-full inline-block mb-2">
                        <i class="fas fa-exclamation-triangle text-red-600"></i>
                    </div>
                    <h3 class="font-medium text-gray-800">Kasus DBD</h3>
                    <p class="text-sm text-gray-500 mt-1">Laporan kasus DBD di sekitar</p>
                </div>
                
                <div class="report-type-option rounded-lg p-4 text-center cursor-pointer border-2 border-gray-200 transition-colors" 
                     data-value="Lingkungan Kotor">
                    <div class="bg-yellow-100 p-3 rounded-full inline-block mb-2">
                        <i class="fas fa-trash text-yellow-600"></i>
                    </div>
                    <h3 class="font-medium text-gray-800">Lingkungan Kotor</h3>
                    <p class="text-sm text-gray-500 mt-1">Tempat berpotensi sarang nyamuk</p>
                </div>
            </div>
            <input type="hidden" name="jenis_laporan" id="jenis_laporan" required>
        </div>

        <!-- Lokasi Kejadian -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Kecamatan -->
            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                    <i class="fas fa-map-marker-alt text-gray-500 mr-2 text-sm"></i>
                    Kecamatan <span class="text-red-500">*</span>
                </label>
                <select name="kecamatan_id" id="kecamatan" required 
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                    <option value="">Pilih Kecamatan</option>
                    @foreach($kecamatans as $kecamatan)
                        <option value="{{ $kecamatan->id }}" {{ old('kecamatan_id') == $kecamatan->id ? 'selected' : '' }}>
                            {{ $kecamatan->nama_kecamatan }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <!-- Kelurahan -->
            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                    <i class="fas fa-map-marker-alt text-gray-500 mr-2 text-sm"></i>
                    Kelurahan <span class="text-red-500">*</span>
                </label>
                <select name="kelurahan_id" id="kelurahan" required disabled
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm disabled:bg-gray-100">
                    <option value="">Pilih Kelurahan</option>
                </select>
            </div>
            
            <!-- RW -->
            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                    <i class="fas fa-map-marker-alt text-gray-500 mr-2 text-sm"></i>
                    RW <span class="text-red-500">*</span>
                </label>
                <select name="rw_id" id="rw" required disabled
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm disabled:bg-gray-100">
                    <option value="">Pilih RW</option>
                </select>
            </div>
            
            <!-- RT -->
            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                    <i class="fas fa-map-marker-alt text-gray-500 mr-2 text-sm"></i>
                    RT <span class="text-red-500">*</span>
                </label>
                <select name="rt_id" id="rt" required disabled
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm disabled:bg-gray-100">
                    <option value="">Pilih RT</option>
                </select>
            </div>
        </div>
        
        <!-- Alamat Detail -->
        <div class="space-y-2">
            <label class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                <i class="fas fa-map-pin text-gray-500 mr-2 text-sm"></i>
                Alamat Detail <span class="text-red-500">*</span>
            </label>
            <input type="text" name="alamat_detail" required 
                   placeholder="Contoh: Jl. Merdeka No. 10, depan warung Bu Siti" 
                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                   value="{{ old('alamat_detail') }}">
        </div>
        
        <!-- Deskripsi -->
        <div class="space-y-2">
            <label class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                <i class="fas fa-align-left text-gray-500 mr-2 text-sm"></i>
                Deskripsi Lengkap <span class="text-red-500">*</span>
            </label>
            <textarea name="deskripsi" required placeholder="Jelaskan secara detail apa yang terjadi..." 
                      class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm h-32">{{ old('deskripsi') }}</textarea>
        </div>
        
        <!-- Upload Foto -->
        <div class="space-y-2">
            <label class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                <i class="fas fa-camera text-gray-500 mr-2 text-sm"></i>
                Unggah Foto Bukti
            </label>
            <div class="relative">
                <input type="file" name="foto_pelaporan" id="foto_pelaporan" 
                       class="block w-full text-sm text-gray-500
                              file:mr-4 file:py-2 file:px-4
                              file:rounded-lg file:border-0
                              file:text-sm file:font-semibold
                              file:bg-blue-50 file:text-blue-700
                              hover:file:bg-blue-100"
                       accept="image/jpeg,image/png,image/jpg,image/webp">
                <p class="mt-1 text-xs text-gray-500">Format: JPG, PNG, atau WEBP. Maksimal 2MB.</p>
            </div>
            <div id="imagePreviewContainer" class="mt-4 hidden">
                <div class="relative">
                    <img id="imagePreview" src="#" alt="Preview Gambar" class="max-w-full h-auto rounded-lg border">
                    <button type="button" id="removeImage" class="absolute top-2 right-2 bg-red-500 text-white rounded-full p-1 hover:bg-red-600">
                        <i class="fas fa-times text-xs"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Tombol Submit -->
        <div class="flex justify-between pt-3">
            <a href="{{ route('warga.dashboard') }}" 
               class="inline-flex items-center px-5 py-2.5 border border-gray-300 text-sm font-medium rounded-lg shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>
                Kembali
            </a>
            <button type="submit" 
                    class="inline-flex items-center px-5 py-2.5 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                <i class="fas fa-paper-plane mr-2"></i>
                Kirim Laporan
            </button>
        </div>
    </form>
</div>

<style>
    .report-type-option {
        transition: all 0.2s ease;
        border: 2px solid #e5e7eb;
    }
    
    .report-type-option:hover {
        border-color: #3b82f6;
    }
    
    .report-type-option.active {
        border-color: #3b82f6 !important;
        background-color: #eff6ff !important;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
    
    .report-type-option.active .bg-blue-100 { background-color: #dbeafe !important; }
    .report-type-option.active .bg-red-100 { background-color: #fee2e2 !important; }
    .report-type-option.active .bg-yellow-100 { background-color: #fef3c7 !important; }
</style>

@endsection
// Ganti bagian JavaScript di @section('scripts') dengan kode ini:

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    // Report type selection
    $('.report-type-option').click(function() {
        $('.report-type-option').removeClass('active');
        $(this).addClass('active');
        $('#jenis_laporan').val($(this).data('value'));
    });

    // Image preview functionality
    $('#foto_pelaporan').change(function(e) {
        if (e.target.files && e.target.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#imagePreview').attr('src', e.target.result);
                $('#imagePreviewContainer').removeClass('hidden');
            }
            reader.readAsDataURL(e.target.files[0]);
        }
    });

    $('#removeImage').click(function() {
        $('#foto_pelaporan').val('');
        $('#imagePreviewContainer').addClass('hidden');
        $('#imagePreview').attr('src', '#');
    });

    // Hierarchical Dropdown - DIPERBAIKI
    $('#kecamatan').change(function() {
        var kecamatan_id = $(this).val();
        if(kecamatan_id) {
            // Reset dan disable dropdown berikutnya
            $('#kelurahan').html('<option value="">Loading...</option>').prop('disabled', true);
            $('#rw').html('<option value="">Pilih RW</option>').prop('disabled', true);
            $('#rt').html('<option value="">Pilih RT</option>').prop('disabled', true);
            
            $.ajax({
                url: "{{ route('pelaporan.get-kelurahan') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    kecamatan_id: kecamatan_id
                },
                success: function(data) {
                    $('#kelurahan').html(data).prop('disabled', false);
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                    $('#kelurahan').html('<option value="">Error loading data</option>').prop('disabled', true);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Gagal memuat data kelurahan',
                        confirmButtonColor: '#3085d6'
                    });
                }
            });
        } else {
            $('#kelurahan').html('<option value="">Pilih Kelurahan</option>').prop('disabled', true);
            $('#rw').html('<option value="">Pilih RW</option>').prop('disabled', true);
            $('#rt').html('<option value="">Pilih RT</option>').prop('disabled', true);
        }
    });

    $('#kelurahan').change(function() {
        var kelurahan_id = $(this).val();
        if(kelurahan_id) {
            $('#rw').html('<option value="">Loading...</option>').prop('disabled', true);
            $('#rt').html('<option value="">Pilih RT</option>').prop('disabled', true);
            
            $.ajax({
                url: "{{ route('pelaporan.get-rw') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    kelurahan_id: kelurahan_id
                },
                success: function(data) {
                    $('#rw').html(data).prop('disabled', false);
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                    $('#rw').html('<option value="">Error loading data</option>').prop('disabled', true);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Gagal memuat data RW',
                        confirmButtonColor: '#3085d6'
                    });
                }
            });
        } else {
            $('#rw').html('<option value="">Pilih RW</option>').prop('disabled', true);
            $('#rt').html('<option value="">Pilih RT</option>').prop('disabled', true);
        }
    });

    $('#rw').change(function() {
        var rw_id = $(this).val();
        if(rw_id) {
            $('#rt').html('<option value="">Loading...</option>').prop('disabled', true);
            
            $.ajax({
                url: "{{ route('pelaporan.get-rt') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    rw_id: rw_id
                },
                success: function(data) {
                    $('#rt').html(data).prop('disabled', false);
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                    $('#rt').html('<option value="">Error loading data</option>').prop('disabled', true);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Gagal memuat data RT',
                        confirmButtonColor: '#3085d6'
                    });
                }
            });
        } else {
            $('#rt').html('<option value="">Pilih RT</option>').prop('disabled', true);
        }
    });

    // Load old values after validation errors
    const oldKecamatanId = "{{ old('kecamatan_id') }}";
    const oldKelurahanId = "{{ old('kelurahan_id') }}";
    const oldRwId = "{{ old('rw_id') }}";
    const oldRtId = "{{ old('rt_id') }}";
    
    if (oldKecamatanId) {
        $('#kecamatan').val(oldKecamatanId);
        
        if (oldKelurahanId) {
            $.ajax({
                url: "{{ route('pelaporan.get-kelurahan') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    kecamatan_id: oldKecamatanId
                },
                success: function(data) {
                    $('#kelurahan').html(data).prop('disabled', false).val(oldKelurahanId);
                    
                    if (oldRwId) {
                        $.ajax({
                            url: "{{ route('pelaporan.get-rw') }}",
                            type: "POST",
                            data: {
                                _token: "{{ csrf_token() }}",
                                kelurahan_id: oldKelurahanId
                            },
                            success: function(data) {
                                $('#rw').html(data).prop('disabled', false).val(oldRwId);
                                
                                if (oldRtId) {
                                    $.ajax({
                                        url: "{{ route('pelaporan.get-rt') }}",
                                        type: "POST",
                                        data: {
                                            _token: "{{ csrf_token() }}",
                                            rw_id: oldRwId
                                        },
                                        success: function(data) {
                                            $('#rt').html(data).prop('disabled', false).val(oldRtId);
                                        }
                                    });
                                }
                            }
                        });
                    }
                }
            });
        }
    }

    // Set old jenis laporan if exists
    const oldJenisLaporan = "{{ old('jenis_laporan') }}";
    if (oldJenisLaporan) {
        $('.report-type-option[data-value="' + oldJenisLaporan + '"]').addClass('active');
        $('#jenis_laporan').val(oldJenisLaporan);
    }
});
</script>
@endsection