@extends('layouts.app')

@section('title', 'Blug | Manage User')

@section('css')
@endsection

@section('content')
    <div class="ibox ">
        <div class="ibox-title">
            <h5>Manage Penjual</h5>
            <div class="ibox-tools">
            </div>
        </div>
        <div class="ibox-content">
            @if ($errors->any())
                <div class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </div>
            @endif
            <form role="form"
                action="{{ !empty($penjual) ? route('user-penjual.update', ['user_penjual' => $penjual->id]) : route('user-penjual.store') }}"
                method="POST" enctype="multipart/form-data">
                {{ csrf_field() }}
                @if (!empty($penjual))
                    @method('PUT')
                @endif
                <div class="form-group">
                    <label>User</label>
                    <select class="form-control" name="id_user" {{ !empty($penjual) ? 'disabled' : ''}}>
                        @foreach ($user as $item)
                            <option value="{{ $item->id }}"
                                {{ old('id_user', @$item->id == @$penjual->id_user ? 'selected' : '') }}>
                                {{ $item->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Nama Toko</label>
                    <input type="text" placeholder="Masukan nama toko" value="{{ old('nama_toko', @$penjual->nama_toko) }}" name="nama_toko"
                        class="form-control">
                </div>
                <div class="form-group">
                    <label>Alamat Toko</label>
                    <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" name="alamat_toko">{{ old('alamat_toko', @$penjual->alamat_toko) }}</textarea>
                </div>
                <div class="mt-3">
                    <button class="btn btn-sm btn-primary w-100" type="submit"><strong>Simpan</strong></button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('js')
@endsection
