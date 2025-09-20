@extends('layouts.navbar')

@section('title', 'profil')

@section('content')

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    <div id="success-message" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert" style="display: none;">
                        <span class="block sm:inline"></span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="md:col-span-1 flex flex-col items-center">
                            <img class="h-32 w-32 rounded-full object-cover" src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=random&color=fff" alt="Profile Picture">
                            <h3 id="display-name-header" class="mt-4 text-xl font-bold text-gray-900">{{ auth()->user()->name }}</h3>
                            <p id="display-email-header" class="text-sm text-gray-500">{{ auth()->user()->email }}</p>
                        </div>

                        <div class="md:col-span-2">
                            <div class="flex justify-between items-center mb-4">
                                <h4 class="text-lg font-semibold text-gray-800">Detail Profil</h4>
                                <button id="edit-button" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 text-sm">Ubah Data</button>
                            </div>
                            
                            <div id="view-mode" class="space-y-4">
                                <div>
                                    <label class="text-gray-500 text-sm">Nama Lengkap</label>
                                    <p id="display-name" class="text-gray-800 font-medium">{{ auth()->user()->name }}</p>
                                </div>
                                <div>
                                    <label class="text-gray-500 text-sm">Alamat Email</label>
                                    <p id="display-email" class="text-gray-800 font-medium">{{ auth()->user()->email }}</p>
                                </div>
                                <div>
                                    <label class="text-gray-500 text-sm">Alamat</label>
                                    <p id="display-alamat" class="text-gray-800 font-medium">{{ auth()->user()->alamat ?? '-' }}</p>
                                </div>
                                <div>
                                    <label class="text-gray-500 text-sm">Nomor Telepon</label>
                                    <p id="display-no_telp" class="text-gray-800 font-medium">{{ auth()->user()->no_telp ?? '-' }}</p>
                                </div>
                            </div>
                            
                            <div id="edit-mode" class="space-y-4" style="display: none;">
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                                    <input type="text" id="name-input" value="{{ auth()->user()->name }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    <p id="error-name" class="text-red-500 text-xs mt-1"></p>
                                </div>
                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700">Alamat Email</label>
                                    <input type="email" id="email-input" value="{{ auth()->user()->email }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                     <p id="error-email" class="text-red-500 text-xs mt-1"></p>
                                </div>
                                <div>
                                    <label for="alamat" class="block text-sm font-medium text-gray-700">Alamat</label>
                                    <textarea id="alamat-input" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">{{ auth()->user()->alamat }}</textarea>
                                     <p id="error-alamat" class="text-red-500 text-xs mt-1"></p>
                                </div>
                                <div>
                                    <label for="no_telp" class="block text-sm font-medium text-gray-700">Nomor Telepon</label>
                                    <input type="text" id="no_telp-input" value="{{ auth()->user()->no_telp }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                     <p id="error-no_telp" class="text-red-500 text-xs mt-1"></p>
                                </div>
                                <div class="flex justify-end space-x-3">
                                    <button id="cancel-button" class="px-4 py-2 bg-gray-300 text-gray-800 rounded-md hover:bg-gray-400">Batal</button>
                                    <button id="save-button" class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600">Simpan Perubahan</button>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Menunggu sampai seluruh halaman HTML dimuat
        document.addEventListener('DOMContentLoaded', function () {
            // Ambil semua elemen yang kita butuhkan
            const editButton = document.getElementById('edit-button');
            const saveButton = document.getElementById('save-button');
            const cancelButton = document.getElementById('cancel-button');
            const viewMode = document.getElementById('view-mode');
            const editMode = document.getElementById('edit-mode');

            // Fungsi untuk beralih ke mode edit
            editButton.addEventListener('click', function () {
                viewMode.style.display = 'none';
                editMode.style.display = 'block';
                editButton.style.display = 'none';
            });

            // Fungsi untuk membatalkan dan kembali ke mode tampilan
            cancelButton.addEventListener('click', function () {
                viewMode.style.display = 'block';
                editMode.style.display = 'none';
                editButton.style.display = 'block';
                // Bersihkan pesan error jika ada
                document.querySelectorAll('p[id^="error-"]').forEach(p => p.textContent = '');
            });

            // Fungsi untuk menyimpan perubahan
            saveButton.addEventListener('click', async function () {
                // Tampilkan status loading
                saveButton.textContent = 'Menyimpan...';
                saveButton.disabled = true;

                // Kosongkan pesan error sebelumnya
                document.querySelectorAll('p[id^="error-"]').forEach(p => p.textContent = '');

                // Ambil data dari form input
                const formData = {
                    name: document.getElementById('name-input').value,
                    email: document.getElementById('email-input').value,
                    alamat: document.getElementById('alamat-input').value,
                    no_telp: document.getElementById('no_telp-input').value,
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
                        // Jika ada error validasi (status 422)
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
                    
                    // Jika berhasil, update tampilan di mode view
                    document.getElementById('display-name').textContent = data.user.name;
                    document.getElementById('display-email').textContent = data.user.email;
                    document.getElementById('display-alamat').textContent = data.user.alamat || '-';
                    document.getElementById('display-no_telp').textContent = data.user.no_telp || '-';
                    document.getElementById('display-name-header').textContent = data.user.name;
                    document.getElementById('display-email-header').textContent = data.user.email;
                    
                    // Tampilkan pesan sukses
                    const successMessage = document.getElementById('success-message');
                    successMessage.querySelector('span').textContent = data.message;
                    successMessage.style.display = 'block';
                    setTimeout(() => successMessage.style.display = 'none', 3000);

                    // Kembali ke mode tampilan
                    cancelButton.click();

                } catch (error) {
                    console.error('Error:', error);
                } finally {
                    // Kembalikan tombol ke keadaan normal
                    saveButton.textContent = 'Simpan Perubahan';
                    saveButton.disabled = false;
                }
            });
        });
    </script>
@endsection