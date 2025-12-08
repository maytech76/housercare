<x-guest-layout>
    <div class="min-h-screen bg-gray-100 flex items-center justify-center">
        <div class="bg-white rounded-xl shadow-xl p-6 w-full max-w-md text-center">
            <h2 class="text-xl font-bold mb-4">Escanea el código QR</h2>

            <div id="reader" style="width: 100%; max-width: 350px; margin: auto;"></div>
        </div>
    </div>

    @push('scripts')

    <script src="https://unpkg.com/html5-qrcode"></script>
    <script>
        const html5QrCode = new Html5Qrcode("reader");

        const qrCodeSuccessCallback = (decodedText, decodedResult) => {
            console.log(`QR detectado: ${decodedText}`);
            // Aquí puedes hacer lo que necesites con el código
        };

        const config = {
            fps: 10,
            qrbox: 250,
        };

        Html5Qrcode.getCameras().then(devices => {
            if (devices && devices.length) {
                const backCamera = devices.find(device => device.label.toLowerCase().includes('back')) || devices[0];
                html5QrCode.start(
                    backCamera.id,
                    config,
                    qrCodeSuccessCallback,
                    error => {
                        console.warn(`Error al escanear: ${error}`);
                    }
                );
            }
        }).catch(err => {
            console.error("No se pudo acceder a la cámara", err);
        });
    </script>
        
    @endpush

    
</x-guest-layout>
