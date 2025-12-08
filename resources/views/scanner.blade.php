<x-guest-layout>
    @section('title', 'Escáner de QR para Check-in')

    @push('styles')
        <style>
            .scanner-container {
                position: relative;
                max-width: 500px;
                margin: 0 auto;
            }
            .scanner-frame {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                border: 3px solid rgba(59, 130, 246, 0.5);
                box-shadow: 0 0 0 100vmax rgba(0, 0, 0, 0.7);
            }
            #loadingSpinner {
                display: none;
            }
        </style>
    @endpush

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h1 class="text-2xl font-bold text-gray-800 mb-6 text-center">
                        <i class="fas fa-qrcode mr-2"></i> Sistema de Check-in por QR
                    </h1>
                    
                    <div class="scan-instructions text-center text-gray-600 mb-6">
                        <p>Escanea el código QR del invitado para registrar su asistencia</p>
                    </div>
                    
                    <div class="scanner-container border-2 border-gray-300 rounded-lg overflow-hidden mb-6">
                        <video id="preview" class="w-full h-auto"></video>
                        <div class="scanner-frame"></div>
                    </div>
                    
                    <div id="loadingSpinner" class="text-center py-8">
                        <div class="inline-block animate-spin rounded-full h-8 w-8 border-t-2 border-b-2 border-blue-500"></div>
                        <p class="mt-2 text-gray-600">Verificando invitado...</p>
                    </div>
                    
                    <div id="resultContainer" class="result-container mt-6 p-6 bg-gray-50 rounded-lg hidden">
                        <div class="guest-info flex flex-col md:flex-row gap-6 mb-6">
                            <div class="flex-shrink-0">
                                <img id="guestPhoto" class="guest-photo w-32 h-32 rounded-lg object-cover border border-gray-200" src="" alt="Foto del invitado">
                            </div>
                            <div class="guest-details flex-1">
                                <h2 id="guestName" class="text-xl font-semibold text-gray-800"></h2>
                                <div class="grid grid-cols-2 gap-4 mt-2">
                                    <div>
                                        <p class="font-medium text-gray-700">Evento ID:</p>
                                        <p id="eventId" class="text-gray-600"></p>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-700">Check-in:</p>
                                        <p id="checkedInAt" class="text-gray-600"></p>
                                    </div>
                                </div>
                                <div id="statusBadge" class="status-badge inline-block mt-3 px-3 py-1 rounded-full text-sm font-medium"></div>
                            </div>
                        </div>
                        
                        <div class="qr-code mt-6 text-center">
                            <p class="font-medium text-gray-700 mb-2">Código QR registrado:</p>
                            <img id="guestQr" class="inline-block max-w-xs border border-gray-200 p-2 bg-white" src="" alt="Código QR">
                        </div>
                        
                        <div class="action-buttons flex justify-center gap-4 mt-8">
                            <button id="resetBtn" class="px-6 py-2 bg-red-100 text-red-800 rounded-lg hover:bg-red-200 transition flex items-center">
                                <i class="fas fa-redo mr-2"></i> Escanear otro QR
                            </button>
                            <button id="checkinBtn" class="px-6 py-2 bg-green-100 text-green-800 rounded-lg hover:bg-green-200 transition flex items-center hidden">
                                <i class="fas fa-check-circle mr-2"></i> Confirmar Check-in
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', async function() {
                // Elementos UI
                const resultContainer = document.getElementById('resultContainer');
                const loadingSpinner = document.getElementById('loadingSpinner');
                const guestName = document.getElementById('guestName');
                const eventId = document.getElementById('eventId');
                const checkedInAt = document.getElementById('checkedInAt');
                const guestPhoto = document.getElementById('guestPhoto');
                const guestQr = document.getElementById('guestQr');
                const resetBtn = document.getElementById('resetBtn');
                const checkinBtn = document.getElementById('checkinBtn');
                
                let currentGuestId = null;
                let scanner = null;
                let backCamera = null;

                // Función para inicializar el escáner
                async function initScanner() {
                    try {
                        // Verificar que Instascan esté disponible
                        if (typeof Instascan === 'undefined') {
                            throw new Error('La librería de escaneo QR no se cargó correctamente');
                        }

                        scanner = new Instascan.Scanner({ 
                            video: document.getElementById('preview'),
                            mirror: false,
                            scanPeriod: 5,
                            backgroundScan: false
                        });
                        
                        scanner.addListener('scan', async function(content) {
                            loadingSpinner.style.display = 'block';
                            resultContainer.classList.add('hidden');
                            checkinBtn.classList.add('hidden');
                            
                            try {
                                const response = await fetch(`/api/guest/find-by-code/${encodeURIComponent(content)}`);
                                if (!response.ok) throw new Error('Error en la respuesta del servidor');
                                
                                const data = await response.json();
                                
                                if (data.is_valid) {
                                    currentGuestId = data.guest.id;
                                    
                                    // Mostrar datos del invitado
                                    guestName.textContent = data.guest.full_name;
                                    eventId.textContent = data.guest.event_id;
                                    checkedInAt.textContent = data.guest.checked_in_at || 'Pendiente';
                                    guestPhoto.src = data.guest.photo_url || 'https://via.placeholder.com/150';
                                    guestQr.src = data.guest.qr_code_url;
                                    
                                    // Configurar badge de estado
                                    const statusBadge = document.getElementById('statusBadge');
                                    if (data.guest.status === 'checkin') {
                                        statusBadge.textContent = 'CHECK-IN REGISTRADO';
                                        statusBadge.className = 'status-badge inline-block mt-3 px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800';
                                        checkinBtn.classList.add('hidden');
                                    } else {
                                        statusBadge.textContent = 'ASISTENCIA CONFIRMADA';
                                        statusBadge.className = 'status-badge inline-block mt-3 px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800';
                                        checkinBtn.classList.remove('hidden');
                                    }
                                    
                                    resultContainer.classList.remove('hidden');
                                } else {
                                    alert(data.message || 'Código QR no válido');
                                }
                            } catch (error) {
                                console.error('Error:', error);
                                alert('Error al verificar el código QR: ' + error.message);
                            } finally {
                                loadingSpinner.style.display = 'none';
                            }
                        });

                        // Obtener cámaras disponibles
                        const cameras = await Instascan.Camera.getCameras();
                        if (cameras.length > 0) {
                            // Preferir cámara trasera
                            backCamera = cameras.find(c => c.name.toLowerCase().includes('back')) || cameras[0];
                            await scanner.start(backCamera);
                        } else {
                            throw new Error('No se encontraron cámaras disponibles');
                        }
                    } catch (error) {
                        console.error('Error al iniciar escáner:', error);
                        alert('Error al iniciar la cámara: ' + error.message);
                    }
                }
                
                // Botón Reset
                resetBtn.addEventListener('click', function() {
                    currentGuestId = null;
                    resultContainer.classList.add('hidden');
                    checkinBtn.classList.add('hidden');
                    if (scanner && backCamera) {
                        scanner.start(backCamera);
                    }
                });
                
                // Botón Check-in
                checkinBtn.addEventListener('click', async function() {
                    if (!currentGuestId) return;
                    
                    if (!confirm('¿Confirmar check-in para este invitado?')) return;
                    
                    try {
                        checkinBtn.disabled = true;
                        checkinBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Procesando...';
                        
                        const response = await fetch(`/api/guest/${currentGuestId}/checkin`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            }
                        });
                        
                        const data = await response.json();
                        
                        if (data.success) {
                            alert('¡Check-in registrado exitosamente!');
                            document.getElementById('statusBadge').textContent = 'CHECK-IN REGISTRADO';
                            document.getElementById('statusBadge').className = 
                                'status-badge inline-block mt-3 px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800';
                            checkinBtn.classList.add('hidden');
                        } else {
                            alert(data.message || 'Error al registrar check-in');
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        alert('Error de conexión: ' + error.message);
                    } finally {
                        checkinBtn.disabled = false;
                        checkinBtn.innerHTML = '<i class="fas fa-check-circle mr-2"></i> Confirmar Check-in';
                    }
                });
                
                // Inicializar el escáner
                await initScanner();
            });
        </script>
    @endpush
</x-guest-layout>