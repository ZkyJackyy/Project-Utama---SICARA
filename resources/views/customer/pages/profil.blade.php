@extends('layouts.navbar')

@section('title', 'Profil Pengguna')

@section('content')

{{-- Latar belakang pink muda --}}
<div class="bg-[#ECE6DA] min-h-screen py-12">
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            <h2 class="text-2xl font-bold text-gray-800 mb-6 px-4 sm:px-0">Profil Saya</h2>

            {{-- KARTU PROFIL PENGGUNA --}}
            <div class="bg-white overflow-hidden shadow-md sm:rounded-xl border border-gray-200">
                <div class="p-6 md:p-8 bg-white">

                    {{-- Pesan Sukses (Update Profil) --}}
                    <div id="success-message" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg relative mb-6 flex items-center gap-3" role="alert" style="display: none;">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span class="block sm:inline"></span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        {{-- Kolom Kiri: Avatar & Info Singkat --}}
                        <div class="md:col-span-1 flex flex-col items-center text-center">
                            {{-- Avatar --}}
                            <div class="relative">
                                <img class="h-32 w-32 rounded-full object-cover border-4 border-white shadow-lg" src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=f472b6&color=fff&size=128" alt="Profile Picture">
                            </div>
                            <h3 id="display-name-header" class="mt-4 text-xl font-bold text-gray-900">{{ auth()->user()->name }}</h3>
                            <p id="display-email-header" class="text-sm text-gray-500">{{ auth()->user()->email }}</p>

                            <button id="edit-button" class="mt-4 flex items-center gap-2 px-4 py-2 bg-pink-600 text-white rounded-lg hover:bg-pink-700 text-sm font-semibold transition transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:ring-offset-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                                Edit Profil
                            </button>
                        </div>

                        {{-- Kolom Kanan: Detail Profil & Form Edit --}}
                        <div class="md:col-span-2">
                            <div class="mb-6">
                                <h4 class="text-lg font-semibold text-gray-800">Detail Profil</h4>
                            </div>
                            
                            {{-- Mode Tampilan (view-mode) --}}
                            <div id="view-mode" class="space-y-2">
                                <dl class="divide-y divide-gray-100">
                                    <div class="py-3 grid grid-cols-1 md:grid-cols-3 gap-2 items-center">
                                        <dt class="text-sm font-medium text-gray-500">Nama Lengkap</dt>
                                        <dd id="display-name" class="md:col-span-2 text-sm text-gray-900 font-medium">{{ auth()->user()->name }}</dd>
                                    </div>
                                    <div class="py-3 grid grid-cols-1 md:grid-cols-3 gap-2 items-center">
                                        <dt class="text-sm font-medium text-gray-500">Alamat Email</dt>
                                        <dd id="display-email" class="md:col-span-2 text-sm text-gray-900 font-medium">{{ auth()->user()->email }}</dd>
                                    </div>
                                    <div class="py-3 grid grid-cols-1 md:grid-cols-3 gap-2 items-center">
                                        <dt class="text-sm font-medium text-gray-500">Alamat</dt>
                                        <dd id="display-alamat" class="md:col-span-2 text-sm text-gray-900 font-medium">{{ auth()->user()->alamat ?? 'Belum diatur' }}</dd>
                                    </div>
                                    <div class="py-3 grid grid-cols-1 md:grid-cols-3 gap-2 items-center">
                                        <dt class="text-sm font-medium text-gray-500">Nomor Telepon</dt>
                                        <dd id="display-no_hp" class="md:col-span-2 text-sm text-gray-900 font-medium">{{ auth()->user()->no_hp ?? 'Belum diatur' }}</dd>
                                    </div>
                                </dl>
                            </div>
                            
                            {{-- Mode Edit (edit-mode) --}}
                            <div id="edit-mode" class="space-y-4" style="display: none;">
                                <div>
                                    {{-- PERUBAHAN: Label dan Input --}}
                                    <label for="name" class="block text-sm font-semibold text-gray-600">Nama Lengkap</label>
                                    <input type="text" id="name-input" value="{{ auth()->user()->name }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-pink-500 focus:ring-pink-500">
                                    <p id="error-name" class="text-red-500 text-xs mt-1"></p>
                                </div>
                                <div>
                                    {{-- PERUBAHAN: Label dan Input --}}
                                    <label for="email" class="block text-sm font-semibold text-gray-600">Alamat Email</label>
                                    <input type="email" id="email-input" value="{{ auth()->user()->email }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-pink-500 focus:ring-pink-500">
                                    <p id="error-email" class="text-red-500 text-xs mt-1"></p>
                                </div>
                                <div>
                                    {{-- PERUBAHAN: Label dan Input --}}
                                    <label for="alamat" class="block text-sm font-semibold text-gray-600">Alamat</label>
                                    <textarea id="alamat-input" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-pink-500 focus:ring-pink-500">{{ auth()->user()->alamat }}</textarea>
                                    <p id="error-alamat" class="text-red-500 text-xs mt-1"></p>
                                </div>
                                <div>
                                    {{-- PERUBAHAN: Label dan Input --}}
                                    <label for="no_hp" class="block text-sm font-semibold text-gray-600">Nomor Telepon</label>
                                    <input type="text" id="no_hp-input" value="{{ auth()->user()->no_hp }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-pink-500 focus:ring-pink-500">
                                    <p id="error-no_hp" class="text-red-500 text-xs mt-1"></p>
                                </div>
                                <div class="flex justify-end space-x-3 pt-2">
                                    <button id="cancel-button" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 font-semibold text-sm transition focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2">Batal</button>
                                    <button id="save-button" class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 font-semibold text-sm transition focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">Simpan Perubahan</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- FITUR BARU: KARTU GANTI PASSWORD --}}
            <div class="mt-8">
                <div class="bg-white overflow-hidden shadow-md sm:rounded-xl border border-gray-200">
                    <div class="p-6 md:p-8 bg-white">
                        
                        {{-- Judul dan Pesan Dinamis --}}
                        @if($hasPassword)
                            <h4 class="text-lg font-semibold text-gray-800 mb-6">Ganti Password</h4>
                        @else
                            <h4 class="text-lg font-semibold text-gray-800 mb-6">Buat Password</h4>
                            <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded-lg relative mb-4 flex items-center gap-3" role="alert">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <span>Anda belum memiliki password. Silakan buat password untuk login secara manual.</span>
                            </div>
                        @endif

                        {{-- Pesan Sukses/Error Password --}}
                        <div id="password-success-message" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg relative mb-4 flex items-center gap-3" role="alert" style="display: none;">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span class="block sm:inline"></span>
                        </div>
                        <div id="password-error-message" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative mb-4 flex items-center gap-3" role="alert" style="display: none;">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span class="block sm:inline"></span>
                        </div>

                        <form id="password-update-form" class="space-y-4">
                            @csrf
                            @method('PUT')
                            
                            {{-- Tampilkan field ini HANYA JIKA user punya password --}}
                            @if($hasPassword)
                            <div>
                                {{-- PERUBAHAN: Label dan Input --}}
                                <label for="current_password" class="block text-sm font-semibold text-gray-600">Password Saat Ini</label>
                                <input type="password" id="current_password-input" name="current_password" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-pink-500 focus:ring-pink-500" required>
                                <p id="error-current_password" class="text-red-500 text-xs mt-1"></p>
                            </div>
                            @endif
                            
                            <div>
                                {{-- PERUBAHAN: Label dan Input --}}
                                <label for="password" class="block text-sm font-semibold text-gray-600">
                                    @if($hasPassword) Password Baru @else Password @endif
                                </label>
                                <input type="password" id="password-input" name="password" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-pink-500 focus:ring-pink-500" required>
                                <p id="error-password" class="text-red-500 text-xs mt-1"></p>
                            </div>
                            <div>
                                {{-- PERUBAHAN: Label dan Input --}}
                                <label for="password_confirmation" class="block text-sm font-semibold text-gray-600">
                                    @if($hasPassword) Konfirmasi Password Baru @else Konfirmasi Password @endif
                                </label>
                                <input type="password" id="password_confirmation-input" name="password_confirmation" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-pink-500 focus:ring-pink-500" required>
                            </div>
                            <div class="flex justify-end pt-2">
                                <button id="save-password-button" type="submit" class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 font-semibold text-sm transition focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                                    @if($hasPassword) Simpan Password @else Buat Password @endif
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>


{{-- SCRIPT - PERUBAHAN HANYA DI BAGIAN PASSWORD SUCCESS --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // === SCRIPT UNTUK EDIT PROFIL (Sudah Benar, Tidak Diubah) ===
        const editButton = document.getElementById('edit-button');
        const saveButton = document.getElementById('save-button');
        const cancelButton = document.getElementById('cancel-button');
        const viewMode = document.getElementById('view-mode');
        const editMode = document.getElementById('edit-mode');

        editButton.addEventListener('click', function () {
            viewMode.style.display = 'none';
            editMode.style.display = 'block';
            editButton.style.display = 'none';
        });

        cancelButton.addEventListener('click', function () {
            viewMode.style.display = 'block';
            editMode.style.display = 'none';
            editButton.style.display = 'flex';
            document.querySelectorAll('p[id^="error-"]').forEach(p => p.textContent = '');
        });

        saveButton.addEventListener('click', async function () {
            saveButton.textContent = 'Menyimpan...';
            saveButton.disabled = true;
            document.querySelectorAll('p[id^="error-"]').forEach(p => p.textContent = '');

            const formData = {
                name: document.getElementById('name-input').value,
                email: document.getElementById('email-input').value,
                alamat: document.getElementById('alamat-input').value,
                no_hp: document.getElementById('no_hp-input').value,
            };

            try {
                const response = await fetch('{{ route('profile.update') }}', {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(formData)
                });

                const data = await response.json();

                if (!response.ok) {
                    if (response.status === 422) {
                        Object.keys(data.errors).forEach(key => {
                            const errorElement = document.getElementById(`error-${key}`);
                            if (errorElement) {
                                errorElement.textContent = data.errors[key][0];
                            }
                        });
                    }
                    throw new Error(data.message || 'Gagal menyimpan data.');
                }
                
                document.getElementById('display-name').textContent = data.user.name;
                document.getElementById('display-email').textContent = data.user.email;
                document.getElementById('display-alamat').textContent = data.user.alamat || 'Belum diatur';
                document.getElementById('display-no_hp').textContent = data.user.no_hp || 'Belum diatur';
                document.getElementById('display-name-header').textContent = data.user.name;
                document.getElementById('display-email-header').textContent = data.user.email;
                
                const successMessage = document.getElementById('success-message');
                successMessage.querySelector('span').textContent = data.message;
                successMessage.style.display = 'flex';
                setTimeout(() => successMessage.style.display = 'none', 3000);

                cancelButton.click();

            } catch (error) {
                console.error('Error:', error);
            } finally {
                saveButton.textContent = 'Simpan Perubahan';
                saveButton.disabled = false;
            }
        });

       // === SCRIPT BARU UNTUK GANTI PASSWORD (Sudah Benar, dengan Perubahan di 'Jika Sukses') ===
        const passwordForm = document.getElementById('password-update-form');
        const savePasswordButton = document.getElementById('save-password-button');
        const defaultPasswordButtonText = savePasswordButton.textContent; 
        const passwordSuccessMessage = document.getElementById('password-success-message');
        const passwordErrorMessage = document.getElementById('password-error-message');

        passwordForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            savePasswordButton.textContent = 'Menyimpan...';
            savePasswordButton.disabled = true;

            passwordSuccessMessage.style.display = 'none';
            passwordErrorMessage.style.display = 'none';

            const errorCurrentPasswordEl = document.getElementById('error-current_password');
            const errorPasswordEl = document.getElementById('error-password');

            if (errorCurrentPasswordEl) {
                errorCurrentPasswordEl.textContent = '';
            }
            if (errorPasswordEl) {
                errorPasswordEl.textContent = '';
            }

            const currentPasswordInput = document.getElementById('current_password-input');

            const passwordData = {
                current_password: currentPasswordInput ? currentPasswordInput.value : null, 
                password: document.getElementById('password-input').value,
                password_confirmation: document.getElementById('password_confirmation-input').value,
            };

            try {
                const response = await fetch('{{ route('password.update') }}', {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(passwordData)
                });

                const data = await response.json();

                if (!response.ok) {
                    if (response.status === 422) {
                        if (data.errors.current_password && errorCurrentPasswordEl) {
                            errorCurrentPasswordEl.textContent = data.errors.current_password[0];
                        }
                        if (data.errors.password && errorPasswordEl) {
                            errorPasswordEl.textContent = data.errors.password[0];
                        }
                    } else {
                        passwordErrorMessage.querySelector('span').textContent = data.message || 'Gagal mengubah password.';
                        passwordErrorMessage.style.display = 'flex';
                    }
                    throw new Error(data.message || 'Gagal mengubah password.');
                }

                // === PERUBAHAN UTAMA ADA DI SINI ===

                // Jika Sukses
                passwordSuccessMessage.querySelector('span').textContent = data.message || 'Password berhasil diperbarui!';
                passwordSuccessMessage.style.display = 'flex';
                passwordForm.reset(); // Kosongkan form

                // HAPUS timeout lama yang menyembunyikan pesan
                // setTimeout(() => passwordSuccessMessage.style.display = 'none', 3000);

                // BARU: SELALU reload halaman setelah 1.5 detik
                // Ini memberi konfirmasi visual yang jelas bahwa password telah diupdate.
                setTimeout(() => {
                    window.location.reload(); 
                }, 1500); 

                // === AKHIR DARI PERUBAHAN ===

            } catch (error) {
                console.error('Password Update Error:', error);
            } finally {
                savePasswordButton.textContent = defaultPasswordButtonText; 
                savePasswordButton.disabled = false;
            }
        });

    });
</script>
@endsection