@extends('layouts.app')

@section('title', 'Tambah Toko')

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <b>Tambah Toko</b>
                        </div>
                    </div>
                    <hr>
                </div>
                <div class="card-body">
                    <form action="{{ route('master-toko.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-12 mb-3">
                                <label for="form-label">Kode Toko</label>
                                <input type="text" class="form-control @error('kd_toko') is-invalid @enderror"
                                    id="kd_toko" name="kd_toko" placeholder="Kode Toko" value="{{ old('kd_toko') }}">

                                @error('kd_toko')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="col-12 mb-3">
                                <label for="form-label">Nama Toko</label>
                                <input type="text" class="form-control @error('nama_toko') is-invalid @enderror"
                                    id="nama_toko" name="nama_toko" placeholder="Nama Toko" value="{{ old('nama_toko') }}">

                                @error('nama_toko')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="col-12 mb-3">
                                <label for="form-label">Alamat</label>

                                <textarea class="form-control @error('alamat') is-invalid @enderror" name="alamat" id="alamat" placeholder="Alamat">{{ old('alamat') }}</textarea>

                                @error('alamat')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="col-12 mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select @error('status') is-invalid @enderror" name="status"
                                    id="status">
                                    <option value="active" @selected(old('active') == 'active')>Active</option>
                                    <option value="inactive" @selected(old('inactive') == 'inactive')>Inactive</option>
                                </select>

                                @error('status')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="col-12 mb-3">
                                <label for="form-label">Latitude</label>
                                <input type="text" class="form-control @error('latitude') is-invalid @enderror"
                                    id="latitude" name="latitude" placeholder="Latitude" value="{{ old('latitude') }}">

                                @error('latitude')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="col-12 mb-3">
                                <label for="form-label">Longitude</label>
                                <input type="text" class="form-control @error('longitude') is-invalid @enderror"
                                    id="longitude" name="longitude" placeholder="Longitude" value="{{ old('longitude') }}">

                                @error('longitude')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="col-12 mb-3 d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
