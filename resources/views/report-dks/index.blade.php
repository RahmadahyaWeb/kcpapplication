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
                    <a href="" class="btn btn-success">
                        <i class="bx bxs-download me-2"></i>
                        Unduh
                    </a>
                </div>
            </div>
            <hr>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <b> {{ $today = \Carbon\Carbon::now()->format('d-m-Y') }}</b>
            </div>
            @livewire('report-dks-table')
        </div>
    </div>
@endsection
