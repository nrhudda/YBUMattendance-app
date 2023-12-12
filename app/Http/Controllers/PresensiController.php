<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

class PresensiController extends Controller
{
    public function create()
    {
        $hariIni = date("Y-m-d");
        $nik = Auth::guard('pegawai')->user()->nik;
        $cekAbsen =DB::table('presensi')->where('tanggal_presensi', $hariIni)->where('nik',$nik)->count();
        return view('presensi.create', compact('cekAbsen'));
    }

    public function store(Request $request){
        $nik = Auth::guard('pegawai')->user()->nik;
        $tgl_presensi = date("Y-m-d");
        $jam = date("H:i:s");
        $latInstansi = -7.74837155820097 ;
        $longInstansi = 110.35922110421156 ;
        $lokasi = $request->lokasi;
        $lokasiUser = explode(",", $lokasi);
        $latUser = $lokasiUser[0];
        $longUser = $lokasiUser[1];
        $jarak = $this->distance($latInstansi,$longInstansi,$latUser, $longUser);
        $radius = round($jarak["meters"]);

        $cekAbsen = DB::table('presensi')->where('tanggal_presensi', $tgl_presensi)->where('nik',$nik)->count();
        if($cekAbsen >0){
            $ket = "out";
        }else{
            $ket = "in";
        }
        $image = $request->image;
        $folderPath = "public/uploads/absensi/";
        $formatName = $nik."-".$tgl_presensi."-".$ket;
        $image_parts = explode(";base64", $image);
        $image_base64 = base64_decode($image_parts[1]);
        $fileName = $formatName.".png";
        $file = $folderPath . $fileName;

        if($radius > 10){
            echo "error|Maaf anda diluar radius lokasi presensi, Jarak anda ".$radius." meter dari kantor|";
        }else{
            if($cekAbsen>0){
                $data_pulang = [
                    'jam_out' => $jam,
                    'foto_out' => $fileName,
                    'lokasi_out' => $lokasi
                ];
                $updateAbsen =DB::table('presensi')->where('tanggal_presensi',$tgl_presensi)->where('nik', $nik)->update($data_pulang);
                if($updateAbsen){
                    echo "success|Terimakasih, hati-hati di jalan|out";
                    Storage::put($file, $image_base64);
                }else{
                    echo "error|Gagal absen|out";
                }
            }else{
                $data = [
                    'nik' => $nik,
                    'tanggal_presensi' => $tgl_presensi,
                    'jam_in' => $jam,
                    'foto_in' => $fileName,
                    'lokasi_in' => $lokasi
                ];
                $simpan = DB::table('presensi')->insert($data);
                if($simpan){
                    echo "success|Terimakasih, Selamat bekerja|in";
                    Storage::put($file, $image_base64);
                }else{
                    echo "error|Gagal absen|in";
                }
            }
        }
    }
    //menghitung jarak titik koordinat
    function distance($lat1, $lon1, $lat2, $lon2){
        $theta = $lon1 - $lon2;
        $miles = (sin(deg2rad($lat1)) * sin(deg2rad($lat2))) + (cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta)));
        $miles = acos($miles);
        $miles = rad2deg($miles);
        $miles = $miles * 60 * 1.1515;
        $feet = $miles * 5280;
        $yards = $feet / 3;
        $kilometers = $miles * 1.609344;
        $meters = $kilometers * 1000;
        return compact('meters');
        }

    public function editprofile(){
        $nik = Auth::guard('pegawai')->user()->nik;
        $pegawai = DB::table('pegawai')
            ->where('nik', $nik)
            ->first();

        return view('presensi.editprofile', compact('pegawai'));
    }

    public function updateprofile(Request $request){
        $nik = Auth::guard('pegawai')->user()->nik;
        $nama_lengkap = $request->nama_lengkap;
        $no_hp = $request->no_hp;
        $password = Hash::make($request->password);
        $pegawai = DB::table('pegawai')->where('nik', $nik)->first();
        if($request->hasFile('foto')){
            $foto = $nik.".".$request->file('foto')->getClientOriginalExtension();
        }else{
            $foto = $pegawai->foto;
        }
        if(!empty($request->password)){
            $data = [
                'nama_lengkap' => $nama_lengkap,
                'no_hp' => $no_hp,
                'password' => $password,
                'foto' => $foto
            ];
        }else{
            $data = [
                'nama_lengkap' => $nama_lengkap,
                'no_hp' => $no_hp,
                'foto' => $foto
            ];
        }
        $update = DB::table('pegawai')->where('nik', $nik)->update($data);
        if($update){
            if($request->hasFile('foto')){
                $folderPath = "public/uploads/pegawai/";
                $request->file('foto')->storeAs($folderPath, $foto);
            }
            return Redirect::back()->with(['success' => 'Data berhasil diperbarui']);
        }else{
            return Redirect::back()->with(['error' => 'Data gagal diperbarui']);
        }
    }
    public function histori(){
        $namabulan = ["","Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember"];
        return view('presensi.histori', compact('namabulan'));
    }

    public function gethistori(Request $request){
        $bulan = $request->bulan;
        $tahun = $request->tahun;
        $nik = Auth::guard('pegawai')->user()->nik;

        $histori = DB::table('presensi')
            ->whereRaw('MONTH(tanggal_presensi)="'.$bulan.'"')
            ->whereRaw('YEAR(tanggal_presensi)="'.$tahun.'"')
            ->where('nik',$nik)
            ->orderBy('tanggal_presensi')
            ->get();
        return view('presensi.gethistori', compact('histori'));
    }

    public function izin(){
        $nik = Auth::guard('pegawai')->user()->nik;
        $dataizin = DB::table('pengajuan_izin')->where('nik', $nik)->get();
        return view('presensi.izin', compact('dataizin'));
    }

    public function buatizin(){

        return view('presensi.buatizin');
    }

    public function storeizin(Request $request){
        $nik = Auth::guard('pegawai')->user()->nik;
        $tanggalizin = $request->tanggalizin;
        $status = $request->status;
        $keterangan = $request->keterangan;

        $data = [
            'nik' => $nik,
            'tanggal_izin' => $tanggalizin,
            'status' => $status,
            'keterangan' => $keterangan
        ];

        $simpan =DB::table('pengajuan_izin')->insert($data);

        if($simpan){
            return redirect('/presensi/izin')->with(['success'=>'Pengajuan izin sedang diproses']);
        }else{
            return redirect('/presensi/izin')->with(['error'=>'Pengajuan izin gagal diproses']);
        }
    }
}
