<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Helpers\CommunityBPS;
use Carbon\Carbon;
use Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\KodeWilayah;
use App\KodeLevel;
use App\UnitKerja;
use Excel;

class PegawaiController extends Controller
{
    //
    public function index()
    {
        $data_wilayah = KodeWilayah::all();
        $dataPegawai = User::get();
        $dataLevel = KodeLevel::where('level_id','<','9')->get();
        return view('pegawai.index',['dataWilayah'=>$data_wilayah,'dataPegawai'=>$dataPegawai]);
    }
    public function cekCommunity(Request $request)
    {
       $arr = array(
           'error'=>true,
           'pesan'=>'Username tidak valid'
       );
       $h = new CommunityBPS($request->peg_username,$request->peg_password);
       if ($h->errorLogin==false) {
        $arr = array(
            'error'=>false,
            'pesan'=>'Login berhasil'
        );
       }
       return Response()->json($arr);
    }
    public function syncData(Request $request)
    {
        //dd($request->all());
        //cek dulu username berhasil tidak login
        //cek apakah provinsi atau kabkota
        $h = new CommunityBPS($request->peg_username,$request->peg_password);
        if ($h->errorLogin==false)
        {
            //cek dulu apakah tipe wilayah 
            $wilayah = KodeWilayah::where('bps_kode','=',$request->wilayah)->first();
            if ($wilayah->bps_jenis==1)
            {
                //provinsi
                $hasil = $h->get_list_pegawai_provinsi($request->wilayah);
                $tot=0;
                if ($hasil) {
                    //$banyak = count($hasil);
                    for ($i=0;$i<count($hasil);$i++)
                    {
                        if ($i==0) {
                            //pasti kepala
                            if ($hasil[0]!=false) {
                                //cek kepala bps ada atau tidak
                                $count_peg = User::where('nipbps','=',$hasil[0]['nipbps'])->count();
                                $kode_unit = UnitKerja::where('unit_nama','=',$hasil[$i]['satuankerja'])->first();
                                if ($kode_unit)
                                    {
                                        $unit_kode = $kode_unit->unit_kode;
                                    }
                                    else 
                                    { 
                                        $unit_kode = NULL;
                                    }
                                if ($count_peg>0) {
                                    //jika sudah ada update isiannya nama, satuan, urlfoto
                                    $data = User::where('nipbps','=',$hasil[$i]['nipbps'])->first();
                                    $data->nama = $hasil[$i]['nama'];
                                    $data->satuankerja = $hasil[$i]['satuankerja'];
                                    $data->urlfoto = $hasil[$i]['urlfoto'];
                                    $data->level = '5';
                                    $data->kodeunit = $unit_kode;
                                    $data->update();
                                    $tot++;
                                }
                                else {
                                    //belum ada
                                    /*
                                    'nama'=>$nama,
                                    'nipbps'=>$nipbps,
                                    'nippanjang'=>$nippanjang,
                                    'email'=>$email,
                                    'username'=>$username,
                                    'jabatan'=>$jabatan,
                                    'satuankerja'=>$satuankerja,
                                    'alamatkantor'=>$alamatkantor,
                                    'urlfoto'=>$urlfoto
                                    */
                                    $data = new User();
                                    $data->nama = $hasil[0]['nama'];
                                    $data->nipbps = $hasil[0]['nipbps'];
                                    $data->nipbaru = $hasil[0]['nippanjang'];
                                    $data->email = $hasil[0]['email'];
                                    $data->username = $hasil[0]['username'];
                                    $data->jabatan = $hasil[0]['jabatan'];
                                    $data->satuankerja = $hasil[0]['satuankerja'];
                                    $data->urlfoto = $hasil[0]['urlfoto'];
                                    $data->jk = substr($hasil[0]['nippanjang'],-4,1);
                                    $data->level = '5';
                                    $data->kodeunit = $unit_kode;
                                    $data->kodebps = $request->wilayah;
                                    $data->password = bcrypt('null');
                                    $data->save();
                                    $tot++;
                                }                 
                            }
                        }
                        else {
                            //langsung simpan
                            for ($j=0;$j<count($hasil[$i]);$j++)
                                {
                                    $count_peg = User::where('nipbps','=',$hasil[$i][$j]['nipbps'])->count();
                                    $kode_unit = UnitKerja::where('unit_nama','=',$hasil[$i][$j]['satuankerja'])->first();
                                    if ($kode_unit)
                                    {
                                        $unit_kode = $kode_unit->unit_kode;
                                        if ($kode_unit->unit_eselon<3)
                                        {
                                            $level = 5;
                                        }
                                        elseif ($kode_unit->unit_eselon<4)
                                        {
                                            $level = 4;
                                        }
                                        else 
                                        {
                                            $level=2;
                                        }
                                    }
                                    else 
                                    { 
                                        $unit_kode = NULL;
                                        $level = 2;
                                    }
                                    if ($count_peg>0) {
                                        //jika sudah ada update isiannya nama, satuan, urlfoto
                                        $data = User::where('nipbps','=',$hasil[$i][$j]['nipbps'])->first();
                                        $data->nama = $hasil[$i][$j]['nama'];
                                        $data->satuankerja = $hasil[$i][$j]['satuankerja'];
                                        $data->urlfoto = $hasil[$i][$j]['urlfoto'];
                                        $data->kodeunit = $unit_kode;
                                        $data->level = $level;
                                        $data->update();
                                        $tot++;
                                    }
                                    else {
                                        //belum ada
                                        /*
                                        'nama'=>$nama,
                                        'nipbps'=>$nipbps,
                                        'nippanjang'=>$nippanjang,
                                        'email'=>$email,
                                        'username'=>$username,
                                        'jabatan'=>$jabatan,
                                        'satuankerja'=>$satuankerja,
                                        'alamatkantor'=>$alamatkantor,
                                        'urlfoto'=>$urlfoto
                                        */
                                        $data = new User();
                                        $data->nama = $hasil[$i][$j]['nama'];
                                        $data->nipbps = $hasil[$i][$j]['nipbps'];
                                        $data->nipbaru = $hasil[$i][$j]['nippanjang'];
                                        $data->email = $hasil[$i][$j]['email'];
                                        $data->username = $hasil[$i][$j]['username'];
                                        $data->jabatan = $hasil[$i][$j]['jabatan'];
                                        $data->satuankerja = $hasil[$i][$j]['satuankerja'];
                                        $data->urlfoto = $hasil[$i][$j]['urlfoto'];
                                        $data->jk = substr($hasil[$i][$j]['nippanjang'],-4,1);
                                        $data->level = $level;
                                        $data->kodeunit = $unit_kode;
                                        $data->kodebps = $request->wilayah;
                                        $data->password = bcrypt('null');
                                        $data->save();
                                        $tot++;
                                    }   
                                }
                        }
                    }
                    $pesan_error='Data pegawai sebanyak '.$tot.' sudah disync';
                    $pesan_warna='success';
                }
                else {
                    $pesan_error='Data tidak tersedia';
                    $pesan_warna='danger';
                }
                //batas provinsi
            }
            else 
            {
                //kabkota
                $pesan_error="BERHASIL : data pegawai kabkota sudah disinkronisasi";
                $pesan_warna='success';
            }
            //batas true berhasil login
        }
        else
        {
            $pesan_error="ERROR : Username/Password community BPS tidak benar!!";
            $pesan_warna='danger';
        }
        Session::flash('message', $pesan_error);
        Session::flash('message_type', $pesan_warna);
        return redirect()->route('pegawai.list');
    }
}
