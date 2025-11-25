@extends('layouts.navbar')

@section('title', 'Profil Saya | DaraCake')

@section('content')

{{-- Load Leaflet CSS & JS --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

{{-- Background Header --}}
<div class="h-56 bg-gradient-to-r from-[#700207] to-[#8B0000] relative overflow-hidden">
    <div class="absolute inset-0 bg-black/20"></div>
    {{-- Pattern hiasan --}}
    <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full -mr-16 -mt-16 blur-3xl"></div>
    <div class="absolute bottom-0 left-0 w-40 h-40 bg-white/10 rounded-full -ml-10 -mb-10 blur-2xl"></div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-28 pb-16 font-['Poppins'] relative z-10">
    
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        {{-- === KOLOM KIRI (SIDEBAR) === --}}
        <div class="lg:col-span-4">
            <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-100 sticky top-24">
                
                {{-- Avatar Section --}}
                <div class="pt-12 pb-8 px-6 text-center bg-white relative">
                    <div class="absolute top-0 left-0 w-full h-24 bg-gray-50"></div>
                    
                    <div class="relative z-10 inline-block">
                        <img class="h-32 w-32 rounded-full object-cover border-4 border-white shadow-lg" 
                             src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=700207&color=fff&size=128&bold=true" 
                             alt="Profile Picture">
                        
                        {{-- Status Indicator --}}
                        <div class="absolute bottom-2 right-2 bg-green-500 border-4 border-white w-6 h-6 rounded-full" title="Online"></div>
                    </div>

                    <h2 id="display-name-header" class="mt-4 text-2xl font-bold text-gray-900">{{ auth()->user()->name }}</h2>
                    <p id="display-email-header" class="text-sm text-gray-500">{{ auth()->user()->email }}</p>

                    <div class="mt-8">
                        <button id="edit-button" class="w-full flex justify-center items-center gap-2 px-6 py-3.5 bg-[#700207] text-white rounded-xl hover:bg-[#5a0105] transition shadow-lg shadow-red-900/20 font-semibold tracking-wide group">
                            <i class="fa fa-user-edit group-hover:scale-110 transition-transform"></i>
                            Edit Profil & Alamat
                        </button>
                    </div>
                </div>

                {{-- Stats Mini --}}
                <div class="bg-gray-50 px-6 py-4 border-t border-gray-100 grid grid-cols-2 divide-x divide-gray-200">
                    <div class="text-center">
                        <span class="block text-lg font-bold text-[#700207]">Member</span>
                        <span class="text-xs text-gray-500 uppercase tracking-wider font-medium">Status</span>
                    </div>
                    <div class="text-center">
                        <span class="block text-lg font-bold text-[#700207]">{{ auth()->user()->created_at->format('Y') }}</span>
                        <span class="text-xs text-gray-500 uppercase tracking-wider font-medium">Bergabung</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- === KOLOM KANAN (KONTEN UTAMA) === --}}
        <div class="lg:col-span-8 space-y-8">
            
            {{-- Alert Sukses --}}
            <div id="success-message" class="hidden bg-green-50 border-l-4 border-green-500 p-4 rounded-r-xl shadow-sm animate-fade-in-down">
                <div class="flex items-center">
                    <div class="flex-shrink-0"><i class="fas fa-check-circle text-green-500 text-xl"></i></div>
                    <div class="ml-3"><p class="text-green-700 font-medium message-text"></p></div>
                </div>
            </div>

            {{-- CARD 1: INFORMASI PRIBADI --}}
            <div class="bg-white rounded-3xl shadow-md border border-gray-100 overflow-hidden">
                <div class="px-8 py-5 border-b border-gray-100 bg-gray-50 flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-red-100 flex items-center justify-center text-[#700207]">
                        <i class="fa fa-id-card"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800">Informasi Pribadi</h3>
                </div>

                <div class="p-8">
                    
                    {{-- MODE LIHAT --}}
                    <div id="view-mode" class="animate-fade-in">
                        <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-8">
                            <div>
                                <dt class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Nama Lengkap</dt>
                                <dd id="display-name" class="text-lg font-medium text-gray-900 border-b border-gray-100 pb-2">{{ auth()->user()->name }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Email</dt>
                                <dd id="display-email" class="text-lg font-medium text-gray-900 border-b border-gray-100 pb-2">{{ auth()->user()->email }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Nomor Telepon</dt>
                                <dd id="display-no_hp" class="text-lg font-medium text-gray-900 border-b border-gray-100 pb-2">{{ auth()->user()->no_hp ?? '-' }}</dd>
                            </div>
                            <div class="md:col-span-2">
                                <dt class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Alamat Pengiriman</dt>
                                <dd class="text-lg font-medium text-gray-900 bg-gray-50 p-4 rounded-xl border border-gray-100">
                                    <div class="flex items-start gap-3">
                                        <i class="fa fa-map-marker-alt text-[#700207] mt-1"></i>
                                        <span id="display-alamat">{{ auth()->user()->alamat ?? 'Belum ada alamat tersimpan.' }}</span>
                                    </div>
                                    
                                    {{-- Tombol Lihat Peta jika ada koordinat --}}
                                    @if(auth()->user()->latitude)
                                    <div class="mt-3 pt-3 border-t border-gray-200">
                                        <a href="https://www.google.com/maps/search/?api=1&query={{ auth()->user()->latitude }},{{ auth()->user()->longitude }}" 
                                           target="_blank" 
                                           class="inline-flex items-center text-xs font-bold text-[#700207] hover:underline">
                                            Lihat di Google Maps <i class="fa fa-external-link-alt ml-1"></i>
                                        </a>
                                    </div>
                                    @endif
                                </dd>
                            </div>
                        </dl>
                    </div>

                    {{-- MODE EDIT --}}
                    <div id="edit-mode" class="hidden animate-fade-in">
                        <form class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                {{-- Nama --}}
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Lengkap</label>
                                    <div class="relative">
                                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400"><i class="fa fa-user"></i></span>
                                        <input type="text" id="name-input" value="{{ auth()->user()->name }}" 
                                            class="w-full pl-10 rounded-xl border-gray-300 focus:border-[#700207] focus:ring focus:ring-[#700207]/20 transition">
                                    </div>
                                    <p id="error-name" class="text-red-500 text-xs mt-1"></p>
                                </div>

                                {{-- Email --}}
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                                    <div class="relative">
                                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400"><i class="fa fa-envelope"></i></span>
                                        <input type="email" id="email-input" value="{{ auth()->user()->email }}" 
                                            class="w-full pl-10 rounded-xl border-gray-300 focus:border-[#700207] focus:ring focus:ring-[#700207]/20 transition">
                                    </div>
                                    <p id="error-email" class="text-red-500 text-xs mt-1"></p>
                                </div>

                                {{-- No HP --}}
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nomor Telepon / WhatsApp</label>
                                    <div class="relative">
                                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400"><i class="fa fa-phone"></i></span>
                                        <input type="text" id="no_hp-input" value="{{ auth()->user()->no_hp }}" 
                                            class="w-full pl-10 rounded-xl border-gray-300 focus:border-[#700207] focus:ring focus:ring-[#700207]/20 transition">
                                    </div>
                                    <p id="error-no_hp" class="text-red-500 text-xs mt-1"></p>
                                </div>

                                {{-- ALAMAT & PETA --}}
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Alamat Lengkap</label>
                                    
                                    {{-- Tombol Lokasi Saya --}}
                                    <button type="button" onclick="getLocation()" class="mb-3 text-xs bg-blue-50 text-blue-600 px-3 py-2 rounded-lg font-bold hover:bg-blue-100 transition flex items-center gap-2">
                                        <i class="fa fa-crosshairs"></i> Gunakan Lokasi Saya Sekarang
                                    </button>

                                    <textarea id="alamat-input" rows="2" 
                                        class="w-full rounded-xl border-gray-300 focus:border-[#700207] focus:ring focus:ring-[#700207]/20 transition mb-3 bg-gray-50"
                                        placeholder="Alamat akan terisi otomatis saat peta diklik...">{{ auth()->user()->alamat }}</textarea>
                                    <p id="error-alamat" class="text-red-500 text-xs mt-1"></p>

                                    {{-- Hidden Inputs --}}
                                    <input type="hidden" id="latitude-input" value="{{ auth()->user()->latitude }}">
                                    <input type="hidden" id="longitude-input" value="{{ auth()->user()->longitude }}">

                                    {{-- MAP CONTAINER --}}
                                    <div class="relative w-full h-72 rounded-xl border-2 border-gray-200 overflow-hidden shadow-inner group">
                                        <div id="map" class="w-full h-full z-0"></div>
                                        
                                        {{-- Overlay Loading Map --}}
                                        <div id="map-loading" class="absolute inset-0 bg-white/80 flex items-center justify-center z-[500]" style="display: none;">
                                            <span class="text-gray-600 font-medium flex items-center gap-2">
                                                <i class="fa fa-spinner fa-spin"></i> Mencari lokasi...
                                            </span>
                                        </div>

                                        <div class="absolute bottom-3 left-3 bg-white/90 px-3 py-1.5 rounded-lg text-xs font-bold text-gray-600 shadow z-[400] border border-gray-200 pointer-events-none">
                                            <i class="fa fa-map-pin text-[#700207] mr-1"></i> Geser pin untuk sesuaikan
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-100">
                                <button type="button" id="cancel-button" class="px-6 py-2.5 rounded-xl text-gray-600 bg-gray-100 hover:bg-gray-200 font-medium transition">Batal</button>
                                <button type="button" id="save-button" class="px-6 py-2.5 rounded-xl text-white bg-[#700207] hover:bg-[#5a0105] font-medium shadow-lg transition flex items-center gap-2">
                                    <i class="fa fa-save"></i> Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- CARD 2: KEAMANAN (PASSWORD) - TETAP ADA --}}
            <div class="bg-white rounded-3xl shadow-md border border-gray-100 overflow-hidden">
                <div class="px-8 py-5 border-b border-gray-100 bg-gray-50 flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-yellow-100 flex items-center justify-center text-yellow-700">
                        <i class="fa fa-lock"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800">Keamanan Akun</h3>
                </div>
                
                <div class="p-8">
                    @if(!$hasPassword)
                        <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6 rounded-r-lg flex items-start gap-3">
                            <i class="fa fa-info-circle text-blue-500 mt-0.5"></i>
                            <p class="text-sm text-blue-700">
                                <span class="font-bold">Info:</span> Anda login menggunakan Google. Silakan buat password agar bisa login secara manual.
                            </p>
                        </div>
                    @endif

                    {{-- Alert Password --}}
                    <div id="password-success-message" class="hidden bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-4 text-sm font-medium"></div>
                    <div id="password-error-message" class="hidden bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-4 text-sm font-medium"></div>

                    <form id="password-update-form" class="space-y-5 max-w-2xl">
                        @csrf
                        @method('PUT')

                        @if($hasPassword)
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Password Saat Ini</label>
                            <input type="password" name="current_password" id="current_password-input" 
                                class="w-full rounded-xl border-gray-300 focus:border-[#700207] focus:ring focus:ring-[#700207]/20 transition bg-gray-50 focus:bg-white" placeholder="••••••••">
                            <p id="error-current_password" class="text-red-500 text-xs mt-1"></p>
                        </div>
                        @endif

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Password Baru</label>
                                <input type="password" name="password" id="password-input" 
                                    class="w-full rounded-xl border-gray-300 focus:border-[#700207] focus:ring focus:ring-[#700207]/20 transition" placeholder="Minimal 8 karakter">
                                <p id="error-password" class="text-red-500 text-xs mt-1"></p>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Konfirmasi Password</label>
                                <input type="password" name="password_confirmation" id="password_confirmation-input" 
                                    class="w-full rounded-xl border-gray-300 focus:border-[#700207] focus:ring focus:ring-[#700207]/20 transition" placeholder="Ulangi password baru">
                            </div>
                        </div>

                        <div class="pt-2">
                            <button type="submit" id="save-password-button" class="px-6 py-2.5 rounded-xl text-white bg-gray-800 hover:bg-black font-medium shadow-md transition flex items-center gap-2">
                                <i class="fa fa-key"></i> {{ $hasPassword ? 'Ganti Password' : 'Buat Password' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- JAVASCRIPT --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    
    const editButton = document.getElementById('edit-button');
    const saveButton = document.getElementById('save-button');
    const cancelButton = document.getElementById('cancel-button');
    const viewMode = document.getElementById('view-mode');
    const editMode = document.getElementById('edit-mode');
    const successMessage = document.getElementById('success-message');

    // === 1. MAP INITIALIZATION & GEOLOCATION ===
    let map, marker;
    // Default: Padang
    const defaultLat = -0.9471; 
    const defaultLng = 100.4172;

    function initMap() {
        let userLat = document.getElementById('latitude-input').value;
        let userLng = document.getElementById('longitude-input').value;

        // Pakai koordinat user jika ada, jika tidak pakai default
        let lat = userLat ? parseFloat(userLat) : defaultLat;
        let lng = userLng ? parseFloat(userLng) : defaultLng;

        if (map) map.remove();

        map = L.map('map').setView([lat, lng], 18);

        L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
            attribution: 'Tiles &copy; Esri &mdash; Source: Esri, i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EGP, and the GIS User Community'
        }).addTo(map);

        marker = L.marker([lat, lng], {draggable: true}).addTo(map);

        // Event Geser Marker
        marker.on('dragend', function (e) {
            let position = marker.getLatLng();
            updateCoordinates(position.lat, position.lng);
        });

        // Event Klik Peta
        map.on('click', function (e) {
            marker.setLatLng(e.latlng);
            updateCoordinates(e.latlng.lat, e.latlng.lng);
        });
    }

    // Fungsi Geolocation (Lokasi Saat Ini)
    window.getLocation = function() {
        if (navigator.geolocation) {
            document.getElementById('map-loading').style.display = 'flex';
            
            navigator.geolocation.getCurrentPosition(function(position) {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                
                if(map) {
                    map.setView([lat, lng], 16);
                    marker.setLatLng([lat, lng]);
                }
                
                updateCoordinates(lat, lng);
                document.getElementById('map-loading').style.display = 'none';

            }, function(error) {
                alert("Gagal mengambil lokasi. Pastikan GPS aktif.");
                document.getElementById('map-loading').style.display = 'none';
            });
        } else {
            alert("Browser Anda tidak mendukung Geolocation.");
        }
    }

    async function updateCoordinates(lat, lng) {
        document.getElementById('latitude-input').value = lat;
        document.getElementById('longitude-input').value = lng;

        const alamatArea = document.getElementById('alamat-input');
        alamatArea.placeholder = "Sedang mengambil nama jalan...";

        // Ambil Nama Jalan (Reverse Geocoding)
        try {
            const response = await fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lng}`);
            const data = await response.json();
            if(data.display_name) {
                alamatArea.value = data.display_name;
            }
        } catch (error) {
            console.error("Gagal ambil alamat:", error);
        }
    }

    // === 2. UI INTERACTION ===
    
    editButton.addEventListener('click', () => {
        viewMode.classList.add('hidden');
        editMode.classList.remove('hidden');
        editButton.style.display = 'none'; // Hide tombol edit sidebar jika di mobile (optional)
        
        // Render map setelah div terlihat
        setTimeout(() => {
            initMap();
            map.invalidateSize();
        }, 200);
    });

    cancelButton.addEventListener('click', () => {
        viewMode.classList.remove('hidden');
        editMode.classList.add('hidden');
    });

    // Handle Save Profile
    saveButton.addEventListener('click', async function () {
        const originalText = saveButton.innerHTML;
        saveButton.textContent = 'Menyimpan...';
        saveButton.disabled = true;
        
        document.querySelectorAll('p[id^="error-"]').forEach(p => p.textContent = '');

        const formData = {
            name: document.getElementById('name-input').value,
            email: document.getElementById('email-input').value,
            alamat: document.getElementById('alamat-input').value,
            no_hp: document.getElementById('no_hp-input').value,
            latitude: document.getElementById('latitude-input').value,
            longitude: document.getElementById('longitude-input').value,
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
                        const errorEl = document.getElementById(`error-${key}`);
                        if (errorEl) errorEl.textContent = data.errors[key][0];
                    });
                }
                throw new Error(data.message || 'Gagal menyimpan.');
            }

            // Update Tampilan View Mode
            document.getElementById('display-name').textContent = data.user.name;
            document.getElementById('display-email').textContent = data.user.email;
            document.getElementById('display-alamat').textContent = data.user.alamat || '-';
            document.getElementById('display-no_hp').textContent = data.user.no_hp || '-';
            
            document.getElementById('display-name-header').textContent = data.user.name;
            document.getElementById('display-email-header').textContent = data.user.email;

            successMessage.querySelector('.message-text').textContent = data.message;
            successMessage.classList.remove('hidden');
            
            // Scroll to top agar pesan sukses terlihat
            window.scrollTo({ top: 0, behavior: 'smooth' });
            
            setTimeout(() => successMessage.classList.add('hidden'), 3000);

            cancelButton.click();

        } catch (error) {
            console.error(error);
        } finally {
            saveButton.innerHTML = originalText;
            saveButton.disabled = false;
        }
    });


    // --- 3. LOGIKA GANTI PASSWORD (TETAP ADA) ---
    const passForm = document.getElementById('password-update-form');
    const passBtn = document.getElementById('save-password-button');
    const passSuccess = document.getElementById('password-success-message');
    const passError = document.getElementById('password-error-message');

    passForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        const originalText = passBtn.innerHTML;
        passBtn.textContent = 'Memproses...';
        passBtn.disabled = true;
        
        passSuccess.classList.add('hidden');
        passError.classList.add('hidden');
        document.getElementById('error-password').textContent = '';
        if(document.getElementById('error-current_password')) document.getElementById('error-current_password').textContent = '';

        const currentPassInput = document.getElementById('current_password-input');
        const passwordData = {
            current_password: currentPassInput ? currentPassInput.value : null,
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
                    if (data.errors.current_password) {
                        document.getElementById('error-current_password').textContent = data.errors.current_password[0];
                    }
                    if (data.errors.password) {
                        document.getElementById('error-password').textContent = data.errors.password[0];
                    }
                } else {
                    passError.textContent = data.message || 'Gagal mengubah password.';
                    passError.classList.remove('hidden');
                }
                throw new Error('Validasi gagal');
            }

            passSuccess.textContent = data.message || 'Password berhasil diperbarui! Halaman akan dimuat ulang.';
            passSuccess.classList.remove('hidden');
            passForm.reset();

            setTimeout(() => {
                window.location.reload();
            }, 1500);

        } catch (error) {
            console.error(error);
        } finally {
            passBtn.innerHTML = originalText;
            passBtn.disabled = false;
        }
    });

});
</script>

<style>
    .animate-fade-in-down { animation: fadeInDown 0.5s ease-out forwards; }
    @keyframes fadeInDown { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }
    .animate-fade-in { animation: fadeIn 0.3s ease-out forwards; }
    @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
</style>
@endsection