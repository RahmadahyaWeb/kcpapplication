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

                    <div id="debug" class="my-2"></div>

                    <div id="result"></div>
                </div>
                <div class="modal-footer">
                    <button id="start-button" class="btn btn-success">Start Scanning</button>
                    <button id="stop-button" class="btn btn-danger d-none" style="display: none;">Stop Scanning</button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            const html5QrCode = new Html5Qrcode("reader");
            let scanning = false;

            document.getElementById("start-button").addEventListener("click", () => {
                // This method will trigger user permissions
                Html5Qrcode.getCameras().then(devices => {
                    if (devices && devices.length) {
                        var cameraId = devices[0].id;

                        const config = {
                            fps: 10
                        }

                        const qrCodeSuccessCallback = (decodedText, decodedResult) => {
                            /* handle success */
                            document.getElementById("result").innerText =
                                `Decoded text: ${decodedText}`;
                        };

                        html5QrCode.start({
                            facingMode: {
                                exact: "environment"
                            }
                        }, config, qrCodeSuccessCallback).then(() => {
                            scanning = true;
                            // Switch button display
                            document.getElementById("start-button").classList.add('d-none');
                            document.getElementById("stop-button").classList.remove('d-none');
                            document.getElementById("placeholder").classList.add('d-none');
                        }).catch(err => {
                            // Start failed, handle the error
                            document.getElementById("result").innerText =
                                `Decoded text: ${err}`;
                            document.getElementById("debug").innerText =
                                `debug: ${JSON.stringify(devices, null, 2)}`;

                        });
                    }
                }).catch(err => {
                    // handle err
                });
            });

            document.getElementById("stop-button").addEventListener("click", () => {
                if (scanning) {
                    html5QrCode.stop().then(() => {
                        scanning = false;
                        // Switch button display back
                        document.getElementById("start-button").classList.remove('d-none');
                        document.getElementById("stop-button").classList.add('d-none');
                        document.getElementById("placeholder").classList.remove('d-none');
                    }).catch(err => {
                        // Stop failed, handle the error
                    });
                }
            });
        </script>
    @endpush
@endsection
