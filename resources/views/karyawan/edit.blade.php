@extends('layouts.fixplay')

@section('title','Edit Karyawan – Fixplay')
@section('page_title','Edit Karyawan')

@push('styles')
<!-- CSS Cropper.js -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" />

<style>
    .fx-form-card { max-width: 640px; margin: 0 auto; }
    .fx-label { font-size: 13px; color: #d1d5db; margin-bottom: 4px; }
    .fx-input {
        width: 100%; padding: 9px 11px; border-radius: 10px;
        border: 1px solid #1f2937; background: #020617; color: #e5e7eb;
        font-size: 14px; outline: none; transition: border-color 0.15s, box-shadow 0.15s;
    }
    .fx-input:focus { border-color: #7b2ff7; box-shadow: 0 0 0 1px rgba(123, 47, 247, 0.7); }
    .fx-form-actions { display: flex; justify-content: flex-end; gap: 8px; margin-top: 16px; }
    
    .fx-btn-primary, .fx-btn-secondary {
        border-radius: 999px; padding: 8px 16px; font-size: 14px; font-weight: 600;
        display: inline-flex; align-items: center; gap: 6px; border: none; text-decoration: none;
    }
    .fx-btn-primary { background: linear-gradient(135deg, #7b2ff7, #00c6ff); color: #fff; box-shadow: 0 0 18px rgba(59, 130, 246, 0.7); }
    .fx-btn-primary:hover { filter: brightness(1.05); color: #fff; }
    .fx-btn-secondary { background: transparent; border: 1px solid rgba(148, 163, 184, 0.7); color: #e5e7eb; }
    .fx-btn-secondary:hover { background: rgba(148, 163, 184, 0.15); color: #fff; }
    .fx-form-subtitle { font-size: 13px; color: #9ca3af; }

    /* Preview Foto */
    .img-preview-container {
        width: 120px; height: 120px;
        background: #0f172a; border: 2px dashed #334155;
        border-radius: 50%;
        overflow: hidden; margin-top: 10px;
        display: flex; align-items: center; justify-content: center;
        color: #64748b; font-size: 11px; text-align: center;
        position: relative;
    }
    .img-preview-container img { width: 100%; height: 100%; object-fit: cover; }

    /* Cropper Modal Dark Mode */
    .modal-content-dark { background: #0f1020; color: #fff; border: 1px solid #334155; }
    .modal-header-dark { border-bottom: 1px solid #1e293b; }
    .modal-footer-dark { border-top: 1px solid #1e293b; }
    .cropper-view-box, .cropper-face { border-radius: 50%; }
</style>
@endpush

@section('page_content')
<div class="card card-dark fx-form-card">
    <div class="card-body">
        <h5 class="mb-1 fw-bold">Edit Karyawan</h5>
        <div class="fx-form-subtitle mb-3">
            Ubah data karyawan. Foto baru akan otomatis di-crop menjadi rasio 1:1.
        </div>

        <form action="{{ route('karyawan.update', $karyawan) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="nama" class="fx-label">Nama</label>
                <input type="text" id="nama" name="nama" class="fx-input" value="{{ $karyawan->nama }}" placeholder="Nama karyawan">
            </div>

            <div class="mb-3">
                <label for="phone" class="fx-label">Phone</label>
                <input type="text" id="phone" name="phone" class="fx-input" value="{{ $karyawan->phone }}" placeholder="Nomor telepon (opsional)">
            </div>

            <div class="mb-2">
                <label for="fotoInput" class="fx-label">Foto (opsional)</label>
                <input type="file" id="fotoInput" name="foto" class="form-control form-control-sm" accept="image/*">
                
                <div class="d-flex align-items-center gap-3 mt-2">
                    <!-- Preview Foto -->
                    <div class="img-preview-container" id="previewContainer">
                        @if($karyawan->foto)
                            <img src="{{ asset('storage/'.$karyawan->foto) }}" alt="Foto Lama">
                        @else
                            <span>Preview<br>1:1</span>
                        @endif
                    </div>
                    
                    <div class="small text-muted">
                        @if($karyawan->foto)
                            <i class="bi bi-info-circle me-1"></i> Foto saat ini ditampilkan.<br>
                            Upload baru untuk mengganti & menyesuaikan posisi.
                        @else
                            <i class="bi bi-info-circle me-1"></i> Belum ada foto.<br>
                            Upload dan sesuaikan (crop) di sini.
                        @endif
                    </div>
                </div>
            </div>

            <div class="fx-form-actions">
                <a href="{{ route('karyawan.index') }}" class="fx-btn-secondary">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
                <button type="submit" class="fx-btn-primary">
                    <i class="bi bi-save"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL CROPPER -->
<div class="modal fade" id="cropModal" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-content-dark">
            <div class="modal-header modal-header-dark">
                <h5 class="modal-title fs-6 fw-bold">Sesuaikan Foto (1:1)</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <div style="max-height: 400px; overflow: hidden; background: #000;">
                    <img id="imageToCrop" style="max-width: 100%; display: block;">
                </div>
            </div>
            <div class="modal-footer modal-footer-dark">
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-sm btn-primary" id="cropBtn">Potong & Simpan</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- JS Cropper.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>

<script>
    let cropper;
    const inputImage = document.getElementById('fotoInput');
    const imageToCrop = document.getElementById('imageToCrop');
    const previewContainer = document.getElementById('previewContainer');
    const cropModalEl = document.getElementById('cropModal');
    const cropModal = new bootstrap.Modal(cropModalEl);
    const cropBtn = document.getElementById('cropBtn');

    // Simpan foto lama untuk restore jika user batal crop
    const originalPreviewHtml = previewContainer.innerHTML;

    // 1. Saat user memilih file baru
    inputImage.addEventListener('change', function (e) {
        const files = e.target.files;
        if (files && files.length > 0) {
            const file = files[0];
            const reader = new FileReader();
            
            reader.onload = function (e) {
                // Set gambar ke modal
                imageToCrop.src = e.target.result;
                
                // Hapus cropper lama
                if (cropper) cropper.destroy();

                // Tampilkan modal
                cropModal.show();
            };
            reader.readAsDataURL(file);
        }
    });

    // 2. Inisialisasi Cropper saat modal muncul
    cropModalEl.addEventListener('shown.bs.modal', function () {
        cropper = new Cropper(imageToCrop, {
            aspectRatio: 1,       // Paksa 1:1
            viewMode: 2,          // Gambar tetap di dalam canvas
            autoCropArea: 1,
            dragMode: 'move',
            guides: true,
            center: true,
            highlight: false,
            cropBoxMovable: false,
            cropBoxResizable: false,
            toggleDragModeOnDblclick: false,
        });
    });

    // 3. Saat tombol "Potong & Simpan" diklik
    cropBtn.addEventListener('click', function () {
        if (!cropper) return;

        // Ambil hasil crop
        const canvas = cropper.getCroppedCanvas({
            width: 500, height: 500, // Resize output agar ringan
        });

        // Update preview di halaman edit
        previewContainer.innerHTML = '';
        const previewImg = document.createElement('img');
        previewImg.src = canvas.toDataURL();
        previewContainer.appendChild(previewImg);

        // Ganti file input dengan hasil crop (blob)
        canvas.toBlob(function (blob) {
            const file = new File([blob], "avatar_updated.jpg", { type: "image/jpeg" });
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            inputImage.files = dataTransfer.files;

            cropModal.hide();
        }, 'image/jpeg');
    });

    // 4. Jika modal ditutup/dicancel tanpa crop
    cropModalEl.addEventListener('hidden.bs.modal', function () {
        if (cropper) {
            cropper.destroy();
            cropper = null;
        }
        
        // Cek apakah user sudah meng-crop? (lihat apakah preview berubah jadi blob)
        // Jika input masih memiliki file tapi preview belum diupdate (user close modal), reset input
        // Atau jika user batal memilih file baru, kembalikan ke foto lama
        const currentPreviewSrc = previewContainer.querySelector('img')?.src;
        
        // Jika preview masih blob baru (berhasil crop), biarkan.
        // Jika tidak, reset input agar kembali kosong (atau kembali ke state awal)
        if (!currentPreviewSrc || !currentPreviewSrc.startsWith('data:')) {
             inputImage.value = ''; // Reset input file
             previewContainer.innerHTML = originalPreviewHtml; // Kembalikan preview foto lama dari DB
        }
    });
</script>
@endpush