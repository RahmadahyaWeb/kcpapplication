@extends('layouts.app')

@section('title', 'DKS Scan')

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="row align-items-center">
                <div class="col">
                    <b>DKS Monitoring</b>
                </div>
                <div class="col d-flex justify-content-end">
                    <a href="" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                        <i class="bx bx-qr-scan me-2"></i>
                        Scan
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            @livewire('dks-table')
        </div>
    </div>

    <!-- Modal SCAN-->
    <div class="modal fade" id="exampleModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">DKS Scan</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="placeholder" class="placeholder">
                        <p>Click "Start Scanning" to begin.</p>
                    </div>
                    <div id="reader" class="img-fluid"></div>
                    <div id="result" class="mb-5 text-center"></div>
                </div>
                <div class="modal-footer">
                    <button id="start-button" class="btn btn-success">Start Scanning</button>
                    <button id="stop-button" class="btn btn-danger d-none">Stop Scanning</button>
                    <button id="toggle-camera-button" class="btn btn-warning d-none">Switch to Front Camera</button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            const html5QrCode = new Html5Qrcode("reader");
            let scanning = false;
            let isRearCamera = true;
            let currentCameraId = null;
            let devices = [];

            document.getElementById("start-button").addEventListener("click", () => {
                Html5Qrcode.getCameras().then(camDevices => {
                    devices = camDevices; // Simpan daftar perangkat kamera
                    if (devices && devices.length) {
                        currentCameraId = devices[0].id; // Gunakan kamera pertama
                        startScanning(currentCameraId);
                    }
                }).catch(err => {
                    console.error(`Error fetching cameras: ${err}`);
                });
            });

            document.getElementById("stop-button").addEventListener("click", () => {
                if (scanning) {
                    stopScanning();
                }
            });

            document.getElementById("toggle-camera-button").addEventListener("click", () => {
                isRearCamera = !isRearCamera; // Ganti status kamera
                const newCameraId = isRearCamera ? currentCameraId : getFrontCameraId(devices);
                stopScanning().then(() => {
                    startScanning(newCameraId);
                });
            });

            function startScanning(cameraId) {
                html5QrCode.start(
                    cameraId, {
                        facingMode: isRearCamera ? {
                            exact: "environment"
                        } : {
                            exact: "environment"
                        }
                    },
                    (decodedText, decodedResult) => {
                        document.getElementById("result").innerText = `Decoded text: ${decodedText}`;
                    },
                    (errorMessage) => {
                        console.warn(`QR Code scan error: ${errorMessage}`);
                    }
                ).then(() => {
                    scanning = true;
                    document.getElementById("start-button").classList.add('d-none');
                    document.getElementById("stop-button").classList.remove('d-none');
                    document.getElementById("toggle-camera-button").classList.remove('d-none');
                    document.getElementById("placeholder").classList.add('d-none');
                }).catch(err => {
                    console.error(`Unable to start scanning: ${err}`);
                });
            }

            function stopScanning() {
                return html5QrCode.stop().then(() => {
                    scanning = false;
                    document.getElementById("start-button").classList.remove('d-none');
                    document.getElementById("stop-button").classList.add('d-none');
                    document.getElementById("toggle-camera-button").classList.add('d-none');
                    document.getElementById("placeholder").classList.remove('d-none');
                }).catch(err => {
                    console.error(`Unable to stop scanning: ${err}`);
                });
            }

            function getFrontCameraId(devices) {
                const frontCamera = devices.find(device => device.label.toLowerCase().includes("front"));
                return frontCamera ? frontCamera.id : devices[0].id; // Jika tidak ditemukan, gunakan kamera pertama
            }
        </script>
    @endpush
@endsection
