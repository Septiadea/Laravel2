@extends('layouts.warga')
@section('title', 'Dashboard - DengueCare')

@section('content')
    <!-- Welcome Section -->
    <div class="mb-8 animate-fade-in">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Selamat Datang, 
            <span class="text-blue-600">{{ Auth::user()->nama_lengkap ?? 'Warga' }}</span>!
        </h1>
        <p class="text-lg text-gray-600">Bagaimana keadaan Anda hari ini?</p>
    </div>

    <!-- Daily Complaint Form -->
    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-8 rounded animate-fade-in">
            <p>{{ session('success') }}</p>
        </div>
    @endif
    
    @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-8 rounded animate-fade-in">
            <p>{{ session('error') }}</p>
        </div>
    @endif
    
    @if($sudahIsiKeluhan)
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-8 rounded animate-fade-in">
            <p>Anda sudah mengisi keluhan hari ini. Terima kasih!</p>
        </div>
    @else
        <div class="bg-white rounded-xl shadow-md p-6 mb-8 max-w-2xl mx-auto animate-slide-in">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">Form Keluhan Harian</h2>
            <form id="keluhanForm" action="{{ route('keluhan.store') }}" method="POST">
                @csrf
                <!-- Step 1: Temperature -->
                <div class="step active">
                    <label class="block text-gray-700 mb-2">Suhu Tubuh:</label>
                    <select name="suhu" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @for($i = 36; $i <= 42; $i++)
                            <option value="{{ $i }}">{{ $i }}°C</option>
                        @endfor
                    </select>
                </div>

                <!-- Step 2: Rash -->
                <div class="step">
                    <label class="block text-gray-700 mb-2">Kapan ruam muncul setelah demam?</label>
                    <select name="ruam" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="Tidak Ada">Tidak Ada</option>
                        <option value="1 hari">1 hari</option>
                        <option value="2-3 hari">2-3 hari</option>
                        <option value="4-6 hari">4-6 hari</option>
                        <option value="7+ hari">7+ hari</option>
                    </select>
                </div>

                <!-- Step 3: Muscle Pain -->
                <div class="step">
                    <label class="block text-gray-700 mb-2">Apakah mengalami nyeri otot?</label>
                    <select name="nyeri_otot" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="Ya">Ya</option>
                        <option value="Tidak">Tidak</option>
                    </select>
                </div>

                <!-- Step 4: Nausea -->
                <div class="step">
                    <label class="block text-gray-700 mb-2">Apakah mengalami mual/muntah?</label>
                    <select name="mual" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="Ya">Ya</option>
                        <option value="Tidak">Tidak</option>
                    </select>
                </div>

                <!-- Step 5: Eye Pain -->
                <div class="step">
                    <label class="block text-gray-700 mb-2">Nyeri di belakang mata?</label>
                    <select name="nyeri_belakang_mata" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="Ya">Ya</option>
                        <option value="Tidak">Tidak</option>
                    </select>
                </div>

                <!-- Step 6: Bleeding -->
                <div class="step">
                    <label class="block text-gray-700 mb-2">Pendarahan (mimisan, gusi berdarah)?</label>
                    <select name="pendarahan" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="Ya">Ya</option>
                        <option value="Tidak">Tidak</option>
                    </select>
                </div>

                <!-- Step 7: Other Symptoms -->
                <div class="step">
                    <label class="block text-gray-700 mb-2">Gejala lainnya:</label>
                    <textarea name="gejala_lain" placeholder="Tulis gejala lain jika ada..." 
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                </div>

                <!-- Form Navigation -->
                <div class="flex justify-between mt-6">
                    <button type="button" id="prevBtn" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition btn-hover hidden">
                        Sebelumnya
                    </button>
                    <button type="button" id="nextBtn" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition btn-hover">
                        Selanjutnya
                    </button>
                    <button type="submit" id="submitBtn" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition btn-hover hidden">
                        Kirim
                    </button>
                </div>
            </form>
            <div id="resultMessage" class="text-center py-4 hidden"></div>
        </div>
    @endif

    <!-- Quick Links -->
    <h3 class="text-xl font-semibold text-gray-800 mb-4 animate-fade-in">Mungkin Ada yang Perlu Anda Lihat</h3>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <a href="{{ route('warga.eventsaya') }}" class="bg-white p-6 rounded-xl shadow-md text-center card-hover transition animate-fade-in hover:bg-blue-50">
            <div class="text-blue-600 mb-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
            <span class="font-medium">Event Saya</span>
        </a>
        
        <a href="{{ route('warga.lokasi') }}" class="bg-white p-6 rounded-xl shadow-md text-center card-hover transition animate-fade-in hover:bg-blue-50">
            <div class="text-blue-600 mb-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </div>
            <span class="font-medium">Lokasi</span>
        </a>
        
        <a href="{{ route('warga.riwayat') }}" class="bg-white p-6 rounded-xl shadow-md text-center card-hover transition animate-fade-in hover:bg-blue-50">
            <div class="text-blue-600 mb-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
            </div>
            <span class="font-medium">Riwayat</span>
        </a>
        
        <a href="{{ route('warga.pelaporan') }}" class="bg-white p-6 rounded-xl shadow-md text-center card-hover transition animate-fade-in hover:bg-blue-50">
            <div class="text-blue-600 mb-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <span class="font-medium">Pelaporan</span>
        </a>
    </div>

    <!-- Events Section -->
    <h3 class="text-xl font-semibold text-gray-800 mb-4 animate-fade-in">Event Tersedia</h3>
    <div class="relative mb-6 animate-fade-in">
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
        </div>
        <input type="text" id="search-bar" placeholder="Cari Event..." 
            class="w-full pl-10 px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="event-grid">
        @foreach($events as $event)
        <div class="bg-white rounded-xl shadow-md overflow-hidden card-hover animate-fade-in transform hover:-translate-y-1 transition duration-300" 
             data-name="{{ strtolower($event->nama_event) }}">
            <div class="p-6">
                <div class="flex justify-between items-start mb-3">
                    <h4 class="text-xl font-semibold text-gray-800">{{ $event->nama_event }}</h4>
                    <span class="px-2 py-1 text-xs rounded-full 
                              {{ $event->tanggal < $today ? 'bg-gray-200 text-gray-600' : 'bg-blue-100 text-blue-800' }}">
                        {{ $event->tanggal < $today ? 'Selesai' : 'Aktif' }}
                    </span>
                </div>
                
                <div class="space-y-2 text-gray-600 mb-4">
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span>{{ \Carbon\Carbon::parse($event->tanggal)->translatedFormat('l, d F Y') }}</span>
                    </div>
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span>{{ $event->lokasi }}</span>
                    </div>
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>{{ $event->waktu }}</span>
                    </div>
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>
                            @php
                                $biaya = (float)$event->biaya;
                            @endphp
                            {{ $biaya == 0 ? 'Gratis' : 'Rp '.number_format($biaya, 0, ',', '.') }}
                        </span>
                    </div>
                </div>
                
                @if(in_array($event->id, $registeredEvents))
                    <button class="w-full px-4 py-2 bg-gray-400 text-white rounded-lg cursor-not-allowed" disabled>
                        <div class="flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Terdaftar
                        </div>
                    </button>
                @else
                <button 
                    type="button"
                    class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition flex items-center justify-center register-event-btn"
                    data-event-id="{{ $event->id }}"
                    data-event-name="{{ $event->nama_event }}"
                >
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Daftar
                </button>
                @endif
            </div>
        </div>
        @endforeach
    </div>

    <!-- Registration Success Modal -->
    <div id="registrationModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-xl p-6 max-w-md w-full mx-4 shadow-2xl transform transition-all duration-300">
            <div class="text-center">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100 mb-4">
                    <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <h3 id="modalTitle" class="text-xl font-semibold text-gray-800 mb-2">Pendaftaran Berhasil!</h3>
                <p id="modalMessage" class="text-gray-600 mb-6">Anda telah terdaftar pada event <span class="font-medium" id="eventName"></span>.</p>
                <div class="flex justify-center space-x-4">
                    <button onclick="closeModal()" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition focus:outline-none focus:ring-2 focus:ring-blue-500">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footer-scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Multi-step form functionality
        const form = document.getElementById('keluhanForm');
        if (form) {
            const steps = form.querySelectorAll('.step');
            const nextBtn = document.getElementById('nextBtn');
            const prevBtn = document.getElementById('prevBtn');
            const submitBtn = document.getElementById('submitBtn');
            const resultMessage = document.getElementById('resultMessage');
            
            let currentStep = 0;
            
            // Show the first step
            showStep(currentStep);
            
            // Next button click handler
            nextBtn.addEventListener('click', function() {
                if (validateStep(currentStep)) {
                    currentStep++;
                    showStep(currentStep);
                }
            });
            
            // Previous button click handler
            prevBtn.addEventListener('click', function() {
                currentStep--;
                showStep(currentStep);
            });
            
            // Submit form handler
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                if (validateStep(currentStep)) {
                    const formData = new FormData(form);
                    
                    // Add CSRF token if not already included
                    if (!formData.has('_token')) {
                        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                        formData.append('_token', csrfToken);
                    }
                    
                    fetch(form.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        form.classList.add('hidden');
                        resultMessage.classList.remove('hidden');
                        
                        if (data.status === 'success') {
                            resultMessage.innerHTML = `
                                <div class="text-green-600 mb-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <h3 class="text-xl font-semibold text-gray-800 mb-2">${data.message.split('<br><br>')[0]}</h3>
                                <div class="bg-blue-50 p-4 rounded-lg mt-4">
                                    <h4 class="font-semibold text-blue-800">Hasil Analisis:</h4>
                                    <p class="text-gray-700">${data.message.split('<br><br>')[1] || ''}</p>
                                </div>
                            `;
                            
                            // Automatically reload after success
                            setTimeout(() => {
                                window.location.reload();
                            }, 10000);
                        } else {
                            resultMessage.innerHTML = `
                                <div class="text-red-600 mb-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <h3 class="text-xl font-semibold text-gray-800 mb-2">${data.message}</h3>
                            `;
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        form.classList.add('hidden');
                        resultMessage.classList.remove('hidden');
                        resultMessage.innerHTML = `
                            <div class="text-red-600 mb-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-800 mb-2">Terjadi kesalahan saat mengirim data. Silakan coba lagi.</h3>
                        `;
                    });
                }
            });
            
            // Function to show the specified step
            function showStep(stepIndex) {
                // Hide all steps
                steps.forEach(step => step.style.display = 'none');
                
                // Show the current step
                steps[stepIndex].style.display = 'block';
                
                // Update buttons
                if (stepIndex === 0) {
                    prevBtn.classList.add('hidden');
                } else {
                    prevBtn.classList.remove('hidden');
                }
                
                if (stepIndex === steps.length - 1) {
                    nextBtn.classList.add('hidden');
                    submitBtn.classList.remove('hidden');
                } else {
                    nextBtn.classList.remove('hidden');
                    submitBtn.classList.add('hidden');
                }
            }
            
            // Function to validate the current step
            function validateStep(stepIndex) {
                const currentStepEl = steps[stepIndex];
                const requiredFields = currentStepEl.querySelectorAll('[required]');
                
                let isValid = true;
                
                requiredFields.forEach(field => {
                    if (!field.value) {
                        isValid = false;
                        field.classList.add('border-red-500');
                        
                        // Add shake animation
                        field.classList.add('animate-shake');
                        setTimeout(() => {
                            field.classList.remove('animate-shake');
                        }, 500);
                    } else {
                        field.classList.remove('border-red-500');
                    }
                });
                
                return isValid;
            }
        }

        // Search functionality
        const searchBar = document.getElementById('search-bar');
        if (searchBar) {
            searchBar.addEventListener('input', function() {
                const searchValue = this.value.toLowerCase();
                const eventCards = document.querySelectorAll('#event-grid > div[data-name]');
                
                eventCards.forEach(card => {
                    const eventName = card.getAttribute('data-name');
                    if (eventName.includes(searchValue)) {
                        card.style.display = 'block';
                        card.classList.add('animate-fade-in');
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        }

        // Event registration functionality
        document.querySelectorAll('.register-event-btn').forEach(button => {
            button.addEventListener('click', function() {
                const eventId = this.getAttribute('data-event-id');
                const eventName = this.getAttribute('data-event-name');
                registerForEvent(eventId, eventName);
            });
        });
    });

    // Event registration function with modal feedback
    function registerForEvent(eventId, eventName) {
        fetch(`/warga/events/register/${eventId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Show success modal
                document.getElementById('eventName').textContent = eventName;
                document.getElementById('registrationModal').classList.remove('hidden');
                
                // Update the button state without reloading
                const buttons = document.querySelectorAll(`.register-event-btn[data-event-id="${eventId}"]`);
                buttons.forEach(button => {
                    button.innerHTML = `
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Terdaftar
                    `;
                    button.classList.remove('bg-blue-600', 'hover:bg-blue-700');
                    button.classList.add('bg-gray-400', 'cursor-not-allowed');
                    button.removeAttribute('data-event-id');
                    button.setAttribute('disabled', 'disabled');
                });
            } else {
                alert(data.message || 'Gagal mendaftar ke event. Silakan coba lagi.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat mendaftar. Silakan coba lagi.');
        });
    }

    function closeModal() {
        document.getElementById('registrationModal').classList.add('hidden');
    }

    // Close modal when clicking outside
    document.getElementById('registrationModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeModal();
        }
    });
</script>
@endsection