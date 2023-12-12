@extends('layouts.presensi')

@section('header')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-beta/css/materialize.min.css">
    <style>
    .datepicker-modal {
        max-height: 480px !important;
        border-radius:10px;
    }
    .datepicker-date-display{
        background-color: #1E74FD !important;
        color: #fff !important;

    }
    .datepicker-table td.is-today,.datepicker-cancel, .datepicker-done,.dropdown-content li>a, .dropdown-content li>span{
        color:#1E74FD !important;
    }

    .datepicker-table td.is-selected{
        background-color: #1E74FD !important;
        color: #fff !important;
    }
    </style>
    <!-- App Header -->
    <div class="appHeader bg-primary text-light">
        <div class="left">
            <a href="javascript:;" class="headerButton goBack">
                <ion-icon name="chevron-back-outline"></ion-icon>
            </a>
        </div>
        <div class="pageTitle">Pengajuan Izin</div>
        <div class="right"></div>
    </div>
@endsection

@section('content')
    <div class="row" style="margin-top: 70px">
        <div class="col">
            <form method="POST" action="/presensi/storeizin" id="formIzin" enctype="multipart/form-data">
            @csrf
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <input type="text" id="tanggalizin" name="tanggalizin" class="form-control datepicker" placeholder="tanggal" name="" id="">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <select name="status" id="status" class="form-control">
                                <option value="">Izin atau sakit</option>
                                <option value="i">Izin</option>
                                <option value="s">Sakit</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <textarea name="keterangan" id="keterangan" cols="30" rows="5" class="form-control" placeholder="keterangan"></textarea>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary w-100">ajukan</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('myscript')
<script>
    var currYear = (new Date()).getFullYear();
    $(document).ready(function() {
        $(".datepicker").datepicker({
            format: "yyyy-mm-dd"
        });
        $("#formIzin").submit(function(){
            var tanggalizin = $("#tanggalizin").val();
            var status = $("#status").val();
            var keterangan = $("#keterangan").val();
            if(tanggalizin==""){
                Swal.fire({
                        title: 'Opps!',
                        text: 'Tanggal tidak boleh kosong',
                        icon: 'warning'
                    });
                return false;
            }else if(status==""){
                Swal.fire({
                        title: 'Opps!',
                        text: 'status tidak boleh kosong',
                        icon: 'warning'
                    });
                return false;
            }else if(keterangan==""){
                Swal.fire({
                        title: 'Opps!',
                        text: 'keterangan tidak boleh kosong',
                        icon: 'warning'
                    });
                return false;
            }
        });
    });

</script>
@endpush

