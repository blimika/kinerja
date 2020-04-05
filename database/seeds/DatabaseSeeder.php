<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        DB::table('t_level')->delete();
        //insert some dummy records
        DB::table('t_level')->insert(array(
        array('level_id'=>'1', 'level_nama'=>'Pemantau'),
        array('level_id'=>'2', 'level_nama'=>'Staf'),
        array('level_id'=>'3', 'level_nama'=>'Kasi/Kasubbag'),
        array('level_id'=>'4', 'level_nama'=>'Kabid/Kabag'),
        array('level_id'=>'5', 'level_nama'=>'Kepala Provinsi'),
        array('level_id'=>'9', 'level_nama'=>'Superadmin'),
         ));
        DB::table('t_jenis')->delete();
        //insert some dummy records
        DB::table('t_jenis')->insert(array(
        array('jenis_kode'=>'1', 'jenis_nama'=>'Provinsi'),
        array('jenis_kode'=>'2', 'jenis_nama'=>'Kabkota'),
         ));
         DB::table('t_jk')->delete();
        //insert some dummy records
        DB::table('t_jk')->insert(array(
        array('jk_id'=>'1', 'jk_nama'=>'Laki-laki'),
        array('jk_id'=>'2', 'jk_nama'=>'Perempuan'),
         ));
         //add superadmin
         DB::table('users')->delete();
        //insert some dummy records
        DB::table('users')->insert(array(
        array('nama'=>'Super Admin', 'password'=>bcrypt('super'),'nipbps'=>'520000000','nipbaru'=>'520000000','email'=>'admin@bpsntb.id','username'=>'admin','jabatan'=>'Kepala','satuankerja'=>'Admin BPSNTB','kodeunit'=>'52000','kodebps'=>'5200','urlfoto'=>'https://via.placeholder.com/100x100','jk'=>'1','aktif'=>'1','level'=>'9','isLokal'=>'1','created_at'=>NOW(),'updated_at'=>NOW()),
         ));

        //golongan
        DB::table('t_gol')->delete();
        //insert some dummy records
        DB::table('t_gol')->insert(array(
        array('gol_id'=>'11', 'gol_nama'=>'I/a', 'gol_pangkat'=> 'JURU MUDA'),
        array('gol_id'=>'12', 'gol_nama'=>'I/b', 'gol_pangkat'=>'JURU MUDA TINGKAT I'),
        array('gol_id'=>'13', 'gol_nama'=>'I/c', 'gol_pangkat'=>'JURU'),
        array('gol_id'=>'14', 'gol_nama'=>'I/d', 'gol_pangkat'=>'JURU TINGKAT I'),
        array('gol_id'=>'21', 'gol_nama'=>'II/a','gol_pangkat'=> 'PENGATUR MUDA'),
        array('gol_id'=>'22', 'gol_nama'=>'II/b', 'gol_pangkat'=>'PENGATUR MUDA TINGKAT I'),
        array('gol_id'=>'23', 'gol_nama'=>'II/c', 'gol_pangkat'=>'PENGATUR'),
        array('gol_id'=>'24', 'gol_nama'=>'II/d', 'gol_pangkat'=>'PENGATUR TINGKAT I'),
        array('gol_id'=>'31', 'gol_nama'=>'III/a', 'gol_pangkat'=>'PENATA MUDA'),
        array('gol_id'=>'32', 'gol_nama'=>'III/b', 'gol_pangkat'=>'PENATA MUDA TINGKAT I'),
        array('gol_id'=> '33', 'gol_nama'=>'III/c','gol_pangkat'=> 'PENATA'),
        array('gol_id'=> '34', 'gol_nama'=>'III/d','gol_pangkat'=> 'PENATA TINGKAT I'),
        array('gol_id'=>'41', 'gol_nama'=>'IV/a', 'gol_pangkat'=>'PEMBINA'),
        array('gol_id'=> '42', 'gol_nama'=>'IV/b', 'gol_pangkat'=>'PEMBINA TINGKAT I'),
        array('gol_id'=> '43', 'gol_nama'=>'IV/c', 'gol_pangkat'=>'PEMBINA UTAMA MUDA'),
        array('gol_id'=>'44', 'gol_nama'=>'IV/d', 'gol_pangkat'=>'PEMBINA UTAMA MADYA'),
        array('gol_id'=>'45', 'gol_nama'=>'IV/e', 'gol_pangkat'=>'PEMBINA UTAMA'),
         ));
        
        DB::table('t_unitkerja')->delete();
         //insert some dummy records
         DB::table('t_unitkerja')->insert(array(
         array('unit_kode'=>'52000', 'unit_nama'=>'BPS Provinsi NTB', 'unit_parent'=>NULL, 'unit_jenis'=>'1', 'unit_eselon'=> '2'),
         array('unit_kode'=>'52510', 'unit_nama'=>'Bagian Tata Usaha', 'unit_parent'=>'52000', 'unit_jenis'=>'1','unit_eselon'=> '3'),
         array('unit_kode'=>'52511', 'unit_nama'=>'Subbagian Bina Program', 'unit_parent'=>'52510', 'unit_jenis'=>'1', 'unit_eselon'=>'4'),
         array('unit_kode'=>'52512', 'unit_nama'=>'Subbagian Kepegawaian & Hukum', 'unit_parent'=>'52510', 'unit_jenis'=>'1',  'unit_eselon'=>'4'),
         array('unit_kode'=>'52513', 'unit_nama'=>'Subbagian Keuangan', 'unit_parent'=>'52510', 'unit_jenis'=>'1', 'unit_eselon'=>'4'),
         array('unit_kode'=>'52514', 'unit_nama'=>'Subbagian Umum', 'unit_parent'=>'52510', 'unit_jenis'=>'1', 'unit_eselon'=>'4'),
         array('unit_kode'=>'52515', 'unit_nama'=>'Subbagian Pengadaan Barang/Jasa', 'unit_parent'=>'52510','unit_jenis'=>'1', 'unit_eselon'=>'4'),
         array('unit_kode'=>'52520', 'unit_nama'=>'Bidang Statistik Sosial', 'unit_parent'=>'52000', 'unit_jenis'=>'1','unit_eselon'=> '3'),
         array('unit_kode'=>'52521', 'unit_nama'=>'Seksi Statistik Kependudukan', 'unit_parent'=>'52520', 'unit_jenis'=>  '1',  'unit_eselon'=>'4'),
         array('unit_kode'=>'52522', 'unit_nama'=>'Seksi Statistik Ketahanan Sosial', 'unit_parent'=>'52520', 'unit_jenis'=> '1',  'unit_eselon'=>'4'),
         array('unit_kode'=>'52523', 'unit_nama'=>'Seksi Statistik Kesejahteraan Rakyat', 'unit_parent'=>'52520', 'unit_jenis'=> '1',  'unit_eselon'=>'4'),
         array('unit_kode'=>'52530', 'unit_nama'=>'Bidang Statistik Produksi', 'unit_parent'=>'52000','unit_jenis'=>'1','unit_eselon'=> '3'),
         array('unit_kode'=>'52531', 'unit_nama'=>'Seksi Statistik Pertanian', 'unit_parent'=>'52530','unit_jenis'=> '1', 'unit_eselon'=> '4'),
         array('unit_kode'=>'52532', 'unit_nama'=>'Seksi Statistik Industri', 'unit_parent'=>'52530','unit_jenis'=> '1',  'unit_eselon'=>'4'),
         array('unit_kode'=>'52533', 'unit_nama'=>'Seksi Statistik Pertambangan, Energi dan Konstruksi ','unit_parent'=> '52530','unit_jenis'=> '1', 'unit_eselon'=>'4'),
         array('unit_kode'=>'52540', 'unit_nama'=>'Bidang Statistik Distribusi', 'unit_parent'=>'52000','unit_jenis'=> '1', 'unit_eselon'=> '3'),
         array('unit_kode'=>'52541', 'unit_nama'=>'Seksi Statistik Harga Konsumen dan Harga Perdagangan Besar', 'unit_parent'=>'52540','unit_jenis'=>  '1','unit_eselon'=> '4'),
         array('unit_kode'=>'52542', 'unit_nama'=>'Seksi Statistik Keuangan Dan Harga Produsen', 'unit_parent'=>'52540', 'unit_jenis'=>  '1', 'unit_eselon'=>'4'),
         array('unit_kode'=>'52543', 'unit_nama'=>'Seksi Statistik Niaga dan Jasa', 'unit_parent'=>'52540','unit_jenis'=>'1','unit_eselon'=> '4'),
         array('unit_kode'=>'52550', 'unit_nama'=>'Bidang Neraca Wilayah dan Analisis Statistik', 'unit_parent'=>'52000','unit_jenis'=> '1','unit_eselon'=>'3'),
         array('unit_kode'=>'52551', 'unit_nama'=>'Seksi Neraca Produksi','unit_parent'=> '52550','unit_jenis'=>  '1', 'unit_eselon'=>'4'),
         array('unit_kode'=>'52552', 'unit_nama'=>'Seksi Neraca Konsumsi','unit_parent'=> '52550', 'unit_jenis'=> '1', 'unit_eselon'=> '4'),
         array('unit_kode'=>'52553', 'unit_nama'=>'Seksi Analisis Statistik Lintas Sektor', 'unit_parent'=>'52550', 'unit_jenis'=>  '1', 'unit_eselon'=> '4'),
         array('unit_kode'=>'52560', 'unit_nama'=>'Bidang IPDS', 'unit_parent'=>'52000', 'unit_jenis'=> '1','unit_eselon'=> '3'),
         array('unit_kode'=>'52561', 'unit_nama'=>'Seksi Integrasi Pengolahan Data', 'unit_parent'=>'52560','unit_jenis'=>'1', 'unit_eselon'=> '4'),
         array('unit_kode'=>'52562', 'unit_nama'=>'Seksi Jaringan dan Rujukan Statistik', 'unit_parent'=>'52560','unit_jenis'=>'1','unit_eselon'=>  '4'),
         array('unit_kode'=>'52563', 'unit_nama'=>'Seksi Diseminasi dan Layanan Statistik', 'unit_parent'=>'52560','unit_jenis'=>'1', 'unit_eselon'=> '4'),
          ));
          //kode bps
         DB::table('t_kodebps')->delete();
         //insert some dummy records
         DB::table('t_kodebps')->insert(array(
         array('bps_kode'=>'5200', 'bps_nama'=>'BPS Provinsi NTB','bps_jenis'=>'1'),
         array('bps_kode'=>'5201', 'bps_nama'=>'BPS Kabupaten Lombok Barat','bps_jenis'=>'2'),
         array('bps_kode'=>'5202', 'bps_nama'=>'BPS Kabupaten Lombok Tengah','bps_jenis'=>'2'),
         array('bps_kode'=>'5203', 'bps_nama'=>'BPS Kabupaten Lombok Timur','bps_jenis'=>'2'),
         array('bps_kode'=>'5204', 'bps_nama'=>'BPS Kabupaten Sumbawa','bps_jenis'=>'2'),
         array('bps_kode'=>'5205', 'bps_nama'=>'BPS Kabupaten Dompu','bps_jenis'=>'2'),
         array('bps_kode'=>'5206', 'bps_nama'=>'BPS Kabupaten Bima','bps_jenis'=>'2'),
         array('bps_kode'=>'5207', 'bps_nama'=>'BPS Kabupaten Sumbawa Barat','bps_jenis'=>'2'),
         array('bps_kode'=>'5208', 'bps_nama'=>'BPS Kabupaten Lombok Utara','bps_jenis'=>'2'),
         array('bps_kode'=>'5271', 'bps_nama'=>'BPS Kota Mataram','bps_jenis'=>'2'),
         array('bps_kode'=>'5272', 'bps_nama'=>'BPS Kota Bima','bps_jenis'=>'2'),
          ));
    }
}
