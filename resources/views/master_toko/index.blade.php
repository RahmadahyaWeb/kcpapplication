@extends('layouts.app')

@section('title', 'Master Toko')

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="row align-items-center">
                <div class="col">
                    <b>Master Toko</b>
                </div>
                <div class="col d-flex justify-content-end">
                    <a href="" class="btn btn-primary">
                        Tambah Toko
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            @livewire('master-toko-table')
        </div>
    </div>
@endsection
