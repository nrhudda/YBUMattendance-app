@extends('layouts.presensi')

@section('header')
    <!-- App Header -->
    <div class="appHeader bg-primary text-light">
        <div class="left">
            <a href="javascript:;" class="headerButton goBack">
                <ion-icon name="chevron-back-outline"></ion-icon>
            </a>
        </div>
        <div class="pageTitle">Attendance YBUM</div>
        <div class="right"></div>
    </div>
    <!-- * App Header -->
    <style>
        .camera-capture,
        .camera-capture video{
            display: inline-block;
            width: 100% !important;
            margin: auto;
            height: auto !important;
            border-radius: 10px;
        }
        #map {
            height: 200px;
            border-radius: 10px
        }
    </style>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

@endsection

@section('content')
    <div class="row" style="margin-top: 70px">
        <div class="col">
            <label for=""> titik koordinat : </label>
            <input type="text" name="" id="lokasi"
            style="background: none;border: none">
            <div class="camera-capture"></div>
        </div>
    </div>
    <div class="row">
        <div class="col">
            @if ($cekAbsen>0)
            <button id="takeabsen" class="btn btn-danger btn-block">
                <ion-icon name="camera-outline"></ion-icon>
                Absen Pulang
            </button>
            @else
            <button id="takeabsen" class="btn btn-primary btn-block">
                <ion-icon name="camera-outline"></ion-icon>
                Absen Masuk
            </button>
            @endif
        </div>
    </div>
    <div class="row mt-1">
        <div class="col">
            <div id="map"></div>
        </div>
    </div>

    <audio id="notifikasi_success">
        <source src="{{asset('assets/sound/success_alert.mp3')}}" type="audio/wav">
    </audio>
    <audio id="notifikasi_error">
        <source src="{{asset('assets/sound/error_alert.mp3')}}" type="audio/wav">
    </audio>

@endsection

@push('myscript')
<script>
    var notifikasi_success = document.getElementById('notifikasi_success');
    var notifikasi_error = document.getElementById('notifikasi_error');
    Webcam.set({
        height:480,
        width:640,
        image_format:'jpeg',
        jpeg_quality:80
    });

    Webcam.attach('.camera-capture')

    var lokasi = document.getElementById('lokasi');
    //deteksi lokasi user
    if(navigator.geolocation){
        navigator.geolocation.getCurrentPosition(successCallback, errorCallback);
    }

    function successCallback(position){
        lokasi.value = position.coords.latitude+","+position.coords.longitude;
        var map = L.map('map').setView([position.coords.latitude, position.coords.longitude], 18);
        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 20,
            attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(map);
        var marker = L.marker([position.coords.latitude, position.coords.longitude]).addTo(map);
        var circle = L.circle([-7.748276761569344, 110.35796561144736], {
            color: 'green',
            fillColor: '#72ff93',
            fillOpacity: 0.5,
            radius: 10
        }).addTo(map);
    }

    function errorCallback(){

    }

    $("#takeabsen").click(function(e){
        Webcam.snap(function(uri){
            image = uri;
        });
        var lokasi = $("#lokasi").val();
        $.ajax({
            type: 'POST',
            url: '/presensi/store',
            data: {
                _token: "{{ csrf_token() }}",
                image:image,
                lokasi:lokasi
            },
            cache:false,
            success:function(respond){
                var status = respond.split("|");
                if(status[0]=="success"){
                    notifikasi_success.play();
                    Swal.fire({
                        title: 'Berhasil!',
                        text: status[1],
                        icon: 'success'
                    })
                    setTimeout("location.href='/dashboard'",2000);
                }else{
                    notifikasi_error.play();
                    Swal.fire({
                        title: 'Gagal!',
                        text: status[1],
                        icon: 'error'
                    })
                }
            }
        });
    });
</script>

@endpush
