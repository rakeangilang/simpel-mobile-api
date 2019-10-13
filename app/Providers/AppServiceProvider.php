<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use App\Model\tipe_kegiatan;
use App\Model\peneliti_psb;
use App\Model\peserta_kegiatan;
use App\User;
use Auth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        
        Schema::defaultStringLength(191);
        View::composer('peneliti.menu',function($view){
            $tipekegiatans = tipe_kegiatan::all();
            $view->with('tipekegiatans',$tipekegiatans);
        });

        View::composer('peneliti.menu',function($view){
            $id_pegawai = auth::user()->id_pegawai;
            $peneliti = peneliti_psb::where('id_pegawai',$id_pegawai)->first();
            $id_peneliti = $peneliti->id_peneliti;
            $pesertas = peserta_kegiatan::where([['id_peneliti', $id_peneliti],['status_konfirm','setuju']])->get();
            $today = strtotime(now());
            foreach ($pesertas as $key => $peserta) {
                $tanggal[] = strtotime($peserta->kegiatan->tanggal_akhir);
                if($tanggal[$key] >= $today){
                    $kegiatans[] = $peserta->kegiatan;
                }
            }
            if(!isset($kegiatans)){
                $kegiatans=null;
            }

            $view->with('kegiatans',$kegiatans);
        });

        View::composer('peneliti.navbar',function($view){
            $id_pegawais = auth::user()->id_pegawai;
            $peneliti_psb = peneliti_psb::where('id_pegawai',$id_pegawais)->first();
            $id_penelitis = $peneliti_psb->id_peneliti;
            $pesertass = peserta_kegiatan::where([['status_konfirm', 'menunggu'],['id_peneliti',$id_penelitis]])->get();
            $countpeserta = $pesertass->count();
            if($countpeserta==0){
                $kegiatanss = null;
            }
            else {
                foreach ($pesertass as $peserta) {
                    $kegiatanss[] = $peserta->kegiatan;
                }  
            }
            $view->with('kegiatanss',$kegiatanss);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
