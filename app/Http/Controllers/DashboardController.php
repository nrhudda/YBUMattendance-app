<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $hariini = date("Y-m-d");
        $bulanini = date("m")*1;
        $tahunini = date("Y");
        $nik = Auth::guard('pegawai')->user()->nik;
        $presensihariini = DB::table('presensi')->where('nik',$nik)->where('tanggal_presensi',$hariini)->first();
        $historibulanini = DB::table('presensi')->whereRaw('MONTH(tanggal_presensi)="'.$bulanini.'"')
            ->where('nik',$nik)
            ->whereRaw('YEAR(tanggal_presensi)="'.$tahunini.'"')
            ->orderBy('tanggal_presensi')
            ->get();

        $rekappresensi = DB::table('presensi')
            ->selectRaw('COUNT(nik) as jumlah_hadir, SUM(IF(jam_in > "07:00",1,0)) as jumlah_terlambat')
            ->where('nik',$nik)
            ->whereRaw('MONTH(tanggal_presensi)="'.$bulanini.'"')
            ->whereRaw('YEAR(tanggal_presensi)="'.$tahunini.'"')
            ->first();

        $leaderboard = DB::table('presensi')
            ->join('pegawai', 'presensi.nik', '=', 'pegawai.nik')
                ->where('tanggal_presensi', $hariini)
                ->orderBy('jam_in')
                ->get();
        $namabulan = ["","Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember"];


        return view('dashboard.dashboard', compact('presensihariini','historibulanini','namabulan','bulanini','tahunini','rekappresensi','leaderboard'));
    }
}
