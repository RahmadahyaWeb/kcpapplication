@extends('layouts.app')

@section('title', 'DKS Scan')

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="row align-items-center">
                <div class="col">
                    <b>DKS Monitoring ({{ $today = \Carbon\Carbon::now()->format('d-m-Y') }})</b>
                </div>
                <div class="col d-flex justify-content-end">
                    <a href="" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                        <i class="bx bx-qr-scan me-2"></i>
                        Scan
                    </a>
                </div>
            </div>
            <hr>
        </div>
        <div class="card-body">
            @livewire('dks-table')
        </div>
        <div class="card-footer">
            <small>
                NB: sebelum ingin istirahat, harus menuliskan keterangan <b>IST/ist</b> saat checkout di toko sebelum
                istirahat.
            </small>
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
                    <div id="placeholder" class="placeholder text-center">
                        <p>Click "Start Scanning" to begin.</p>
                    </div>

                    <div id="reader" class="img-fluid mb-3"></div>

                    <div id="result" class="mb-3"></div>

                    <div class="d-grid">
                        <button id="start-button" class="btn btn-success">Start Scanning</button>
                        <button id="stop-button" class="btn btn-danger d-none" style="display: none;">Stop Scanning</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            const html5QrCode = new Html5Qrcode("reader");
            let scanning = false;

            document.getElementById("start-button").addEventListener("click", () => {
                function getQrBoxSize() {
                    const width = window.innerWidth;
                    const height = window.innerHeight;
                    const qrBoxSize = Math.min(width, height) * 0.25;
                    return {
                        width: Math.max(qrBoxSize, 200),
                        height: Math.max(qrBoxSize, 200)
                    };
                }

                Html5Qrcode.getCameras().then(devices => {
                    if (devices && devices.length) {
                        var cameraId = devices[0].id;
                        const config = {
                            aspectRatio: 1,
                            qrbox: getQrBoxSize(),
                        };

                        const qrCodeSuccessCallback = (decodedText, decodedResult) => {
                            const url = new URL(decodedText);
                            const kd_toko = url.searchParams.get('kd_toko');
                            const encrypted = btoa(kd_toko);
                            const redirectUrl = `/dks-scan/${encrypted}`;

                            window.location.href = redirectUrl;
                        };

                        html5QrCode.start({
                            facingMode: {
                                exact: "environment"
                                // exact: "user"
                            }
                        }, config, qrCodeSuccessCallback).then(() => {
                            scanning = true;
                            document.getElementById("start-button").classList.add('d-none');
                            document.getElementById("stop-button").classList.remove('d-none');
                            document.getElementById("placeholder").classList.add('d-none');
                        }).catch(err => {
                            document.getElementById("result").innerText =
                                `Error starting scanner: ${err}`;
                        });
                    } else {
                        document.getElementById("result").innerText = "No camera found.";
                    }
                }).catch(err => {
                    document.getElementById("result").innerText = "Camera access denied or not available.";
                });
            });

            document.getElementById("stop-button").addEventListener("click", () => {
                if (scanning) {
                    html5QrCode.stop().then(() => {
                        scanning = false;
                        document.getElementById("start-button").classList.remove('d-none');
                        document.getElementById("stop-button").classList.add('d-none');
                        document.getElementById("placeholder").classList.remove('d-none');
                    }).catch(err => {
                        document.getElementById("result").innerText = `Error stopping scanner: ${err}`;
                    });
                }
            });
        </script>
    @endpush

@endsection
