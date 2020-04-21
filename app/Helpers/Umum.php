<?php
namespace App\Helpers;

class CommunityBPS 
{
	private $cookie; // cookies 
	private $ch; // curl
	private $username; // username community
	private $password; // password community
	private $nip; // nip bps
	private $isLogin = false;
	public $errorLogin = true;
	
	// CONSTRUCTOR
	function __construct($username, $password){
		$this->cookie = "cookie.txt";
		$this->ch = curl_init();
		$this->username = $username;
		$this->password = $password;
		$this->login();
	}
	
	// DESTRUCTOR
	function __destruct() {
        if($this->ch) curl_close($this->ch);
    }
	
	/**** 
		GET ASN PROFILE METHOD 
		if exists, it will return an array, else will return false
	****/
	public function getprofil($nip){ // $nip = nip bps (example 340057260)
		$postdata = ""; 
		$url="https://community.bps.go.id/portal/index.php?id=2,6,".$nip; 
		$ch = $this->connectcurl($this->ch, $url, $postdata);
		$result = curl_exec ($ch); 
		
		$urlfoto = 'https://community.bps.go.id'.$this->get_string_between($result, '<center><img width=120px src="..', '" ></center>');
		$nama = trim(($this->get_string_between($result, 'Nama Lengkap</td><td width="2px" align="left">:</td><td align="left">', '</td></tr>')));
		$nipbps = $nip;
		$nippanjang = $this->get_string_between($result, $nipbps.' - ', '</td></tr>');
		$email = $this->get_string_between($result, 'Email</td><td width="2px" align="left">:</td><td align="left">', '</td></tr>');
		$username = str_replace("@bps.go.id","",$email);
		$satuankerja = trim($this->get_string_between($result, 'Satuan Kerja</td><td width="2px" align="left" valign="top">:</td><td align="left">', '</td></tr>'));
		$alamatkantor = trim($this->get_string_between($result, 'Alamat Kantor</td><td width="2px" align="left">:</td><td align="left">', '</td></tr>'));
		if ($satuankerja !="") {
			$sat = \explode(" ",$satuankerja);
			if ($sat[0]=='BPS' or $sat[0]=='Bagian' or $sat[0]=='Bidang') {
				$jabatan = 'Kepala';
			}
			else {
				$jabatan = '[Kepala/Staf]';
			}
		}
		return $nama !='' ? array(
			'nama'=>$nama,
			'nipbps'=>$nipbps,
			'nippanjang'=>$nippanjang,
			'email'=>$email,
			'username'=>$username,
			'jabatan'=>$jabatan,
			'satuankerja'=>$satuankerja,
			'alamatkantor'=>$alamatkantor,
			'urlfoto'=>$urlfoto
		) : false;
		
	}
	
	/**** 
		GET ALL ASN PROFILE IN BPS KABKOT 
		if exists, it will return arrays of profile, else will return false
	****/
	public function get_list_pegawai_kabkot($kodekab){  // $kodekab = BPS Kabkot code (example 7206)
		$postdata = ""; 
		$url="https://community.bps.go.id/portal/index.php?id=2,2,0&kab=".$kodekab; 
		$ch = $this->connectcurl($this->ch, $url, $postdata);
		$result = curl_exec ($ch); 
		
		$webpagestart = stripos($result, '<!DOCTYPE');
		$webpage = substr($result,$webpagestart);
		$doc = new \DOMDocument; 
		$doc->loadHTML($webpage, LIBXML_NOWARNING | LIBXML_NOERROR); 
		
		$content_node=$doc->getElementById("tengah");
		$listurlpegawai = array(); // to get ASN nip 
		$div_a_class_nodes=$this->getElementsByClass($content_node, 'div', 'left_box');
		foreach($div_a_class_nodes as $nodess){
			$items = $nodess->getElementsByTagName('a'); 
			foreach($items as $value) 
			{ 
				$attrs = $value->attributes; 
				$listurlpegawai[]=substr($attrs->getNamedItem('href')->nodeValue, -9);
			}

		}
		
		// convert all ASN nip to arrays of profile
		$listpegawai = array();
		foreach($listurlpegawai as $nip){
			$listpegawai[] = $this->getprofil($nip);
		}
		
		return count($listpegawai)>0 ? $listpegawai : false;
	}
	
	
	/**** 
		GET ALL ASN PROFILE IN BPS KABKOT 
		if exists, it will return arrays of profile, else will return false
	****/
	public function get_list_pegawai_provinsi($kodeprov){  // $kodekab = BPS Kabkot code (example 7206)
		$postdata = "org=".$kodeprov; 
		$url="https://community.bps.go.id/portal/index.php?id=2,0,0"; 
		$ch = $this->connectcurl($this->ch, $url, $postdata);
		$result = curl_exec ($ch); 
		
		$webpagestart = stripos($result, '<!DOCTYPE');
		$webpage = substr($result,$webpagestart);
		$doc = new \DOMDocument; 
		$doc->loadHTML($webpage, LIBXML_NOWARNING | LIBXML_NOERROR); 
		
		$content_node=$doc->getElementById("tengah");
		$listurlpegawai = array(); // to get ASN nip 
		$div_a_class_nodes=$this->getElementsByClass($content_node, 'div', 'left_box');
		foreach($div_a_class_nodes as $nodess){
			$items = $nodess->getElementsByTagName('a'); 
			
			foreach($items as $key => $value) 
			{ 
				$attrs = $value->attributes; 
				$listurlpegawai[]=$attrs->getNamedItem('href')->nodeValue;
			}

		}
		
		// convert all ASN nip to arrays of profile
		$listpegawai = array();
		$i = 0;
		foreach($listurlpegawai as $nip){
			$getnip = substr($nip,-9);
			
			if($i==0) {
				if(substr($getnip, -7)=='0000000'){
					$listpegawai[] = false;
					$listpegawai[] = $this->get_sublist_pegawai_provinsi($nip);
				}
				else {
					$listpegawai[] = $this->getprofil($getnip);
				}
			}else{
				if(substr($getnip, -7)=='0000000'){
					$listpegawai[] = $this->get_sublist_pegawai_provinsi($nip);
				}
			}
			
			$i++;
		}
		
		return count($listpegawai)>0 ? $listpegawai : false;
		//return $listurlpegawai;
	}
	
	/**** 
		GET ASN BY QUERY
		it will return array with index listpegawai and pesanerror
	****/
	public function pencarian($query, $wilayah="All"){  // $wilayah = BPS Code
		$postdata = "wil=".$wilayah."&namapg=".trim($query); 
		$url="https://community.bps.go.id/portal/index.php?id=2,5,0"; 
		$ch = $this->connectcurl($this->ch, $url, $postdata);
		$result = curl_exec ($ch); 
		
		$webpagestart = stripos($result, '<!DOCTYPE');
		$webpage = substr($result,$webpagestart);
		$doc = new \DOMDocument; 
		$doc->loadHTML($webpage, LIBXML_NOWARNING | LIBXML_NOERROR); 
		
		$listurlpegawai = array(); // to get ASN nip 
		$div_a_class_nodes=$this->getElementsByClass($doc, 'div', 'left_box');
		
		foreach($div_a_class_nodes as $nodess){
			$items = $nodess->getElementsByTagName('a'); 
			foreach($items as $value) 
			{ 
				$attrs = $value->attributes; 
				$listurlpegawai[]=substr($attrs->getNamedItem('href')->nodeValue, -9);
			}

		}
		
		// convert all ASN nip to arrays of profile
		$listpegawai = array();
		foreach($listurlpegawai as $nip){
			if($nip=='y.back(1)') break;
			$listpegawai[] = $this->getprofil($nip);
		}
		
		$pesanerror = null;
		if(count($listpegawai)==0){
			$pesanerror = trim($this->get_string_between($result, '<div class=pesan_error>', '<br>')); 
		}
		
		$hasil = array(
			'listpegawai'=>$listpegawai,
			'pesanerror'=>$pesanerror
		);
		
		return $hasil;
	}
	
	
	
	
	
	
	
	/*****
		****************************************
			DONT DO ANYTHING WITH CODES BELOW
		****************************************
	******/
	
	// INITIATE LOGIN COMMUNITY BPS TO USE OTHERS METHOD
	private function login() {
		$redirectto = 'https://community.bps.go.id';
		$appname = 'Front Page';
		$appid = '0';
		$remoteip = '0.0.0.0';
		$requesturl = "";
		$postdata = "uname=".$this->username."&pass=".$this->password."&redirectto=".$redirectto."&appname=".$appname."&appid=".$appid."&remoteip=".$remoteip."&requesturl=".$requesturl; 
		$url="https://community.bps.go.id/libs/clogin.php"; 
		
		$ch = $this->connectcurl($this->ch, $url, $postdata);
		$result = curl_exec ($ch); 

		// get cookies after login
		preg_match_all('/^Set-Cookie:\s*([^;]*)/mi', $result, $matches);
		$cookies = array();
		foreach($matches[1] as $item) {
			parse_str($item, $cookie);
			$cookies = array_merge($cookies, $cookie);
		}

		if(isset($cookies['CommunityBPS'])){
			
			$kukis = $cookies['CommunityBPS'];
			$len_char=strlen($kukis)-32;
			$sessionid=substr($kukis,0,$len_char);
			$nip=substr($kukis,0,9);
			$hashkey=substr($kukis,-32);
			
			$this->nip = $nip;
			$this->ch = $ch;
			$this->errorLogin = false;

		}
		else{
			//throw new Exception("Plugin Community BPS stopped because The Credentials is wrong");
			$this->errorLogin = true;
		}
	}
	
	// CONFIG CURL
	private function connectcurl($ch, $url, $postdata){
	
		$cookie="cookie.txt"; 
		curl_setopt ($ch, CURLOPT_URL, $url); 
		curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
		curl_setopt ($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.6) Gecko/20070725 Firefox/2.0.0.6"); 
		curl_setopt ($ch, CURLOPT_TIMEOUT, 60); 
		curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 0); 
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1); 
		curl_setopt ($ch, CURLOPT_COOKIEJAR, $cookie); 
		curl_setopt ($ch, CURLOPT_REFERER, $url); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);

		curl_setopt ($ch, CURLOPT_POSTFIELDS, $postdata); 
		curl_setopt ($ch, CURLOPT_POST, 1); 

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HEADER, 1);
		
		return $ch;
	}
	
	// GET SUBSTRING BETWEEN TWO STRING
	private function get_string_between($string, $start, $end){
		$string = ' ' . $string;
		$ini = strpos($string, $start);
		if ($ini == 0) return '';
		$ini += strlen($start);
		$len = strpos($string, $end, $ini) - $ini;
		return substr($string, $ini, $len);
	}
	
	// GET ELEMENTS OF HTML DOM BY CLASS NAME
	private function getElementsByClass(&$parentNode, $tagName, $className) {
		$nodes=array();

		$childNodeList = $parentNode->getElementsByTagName($tagName);
		for ($i = 0; $i < $childNodeList->length; $i++) {
			$temp = $childNodeList->item($i);
			if (stripos($temp->getAttribute('class'), $className) !== false) {
				$nodes[]=$temp;
			}
		}

		return $nodes;
	}
	
	// GET ALL ASN PROFILE MORE DEEPER
	private function get_sublist_pegawai_provinsi($suburl){ 
		$postdata = ""; 
		$url="https://community.bps.go.id/portal/".$suburl; 
		$ch = $this->connectcurl($this->ch, $url, $postdata);
		$result = curl_exec ($ch); 
		
		$webpagestart = stripos($result, '<!DOCTYPE');
		$webpage = substr($result,$webpagestart);
		$doc = new \DOMDocument; 
		$doc->loadHTML($webpage, LIBXML_NOWARNING | LIBXML_NOERROR); 
		
		$content_node=$doc->getElementById("tengah");
		$listurlpegawai = array(); // to get ASN nip 
		$div_a_class_nodes=$this->getElementsByClass($content_node, 'div', 'left_box');
		foreach($div_a_class_nodes as $nodess){
			$items = $nodess->getElementsByTagName('a'); 
			foreach($items as $value) 
			{ 
				$attrs = $value->attributes; 
				$listurlpegawai[]=substr($attrs->getNamedItem('href')->nodeValue, -9);
			}
		}
		
		// convert all ASN nip to arrays of profile
		$listpegawai = array();
		foreach($listurlpegawai as $nip){
			$listpegawai[] = $this->getprofil($nip);
		}
		
		return count($listpegawai)>0 ? $listpegawai : false;
	}
	
}

//class Tanggal
class Tanggal {
    public static function Panjang($tgl) {
        $bln_panjang = array(1=>"Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember");
        $tahun=date("Y",strtotime($tgl));
	    $tgl_=date("j",strtotime($tgl));
	    $bln_indo=date("n",strtotime($tgl));
        $tanggal= $tgl_ .' '.$bln_panjang[$bln_indo].' '.$tahun;
        return $tanggal;
    }

    public static function Pendek($tgl) {
        $bln_panjang = array(1=>"Jan","Feb","Mar","Apr","Mei","Jun","Jul","Agu","Sep","Okt","Nov","Des");
        $tahun=date("Y",strtotime($tgl));
	    $tgl_=date("j",strtotime($tgl));
	    $bln_indo=date("n",strtotime($tgl));
        $tanggal= $tgl_ .' '.$bln_panjang[$bln_indo].' '.$tahun;
        return $tanggal;
    }

    public static function HariPanjang($tgl) {
        $nama_hari_indo = array (0=> "Minggu", 1=> "Senin", 2=> "Selasa", 3=> "Rabu", 4=> "Kamis", 5=> "Jumat", 6=> "Sabtu");
        $bln_panjang = array(1=>"Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember");
        $tahun=date("Y",strtotime($tgl));
	    $hari=date("w",strtotime($tgl));
	    $tgl_=date("j",strtotime($tgl));
	    $bln_indo=date("n",strtotime($tgl));
        $tanggal= $nama_hari_indo[$hari].', '. $tgl_ .' '.$bln_panjang[$bln_indo].' '.$tahun;
	    return $tanggal;
    }
    public static function HariPendek($tgl) {
        $nama_hari_indo = array (0=> "Min", 1=> "Sen", 2=> "Sel", 3=> "Rab", 4=> "Kam", 5=> "Jum", 6=> "Sab");
        $bln_panjang = array(1=>"Jan","Feb","Mar","Apr","Mei","Jun","Jul","Agu","Sep","Okt","Nov","Des");
        $tahun=date("Y",strtotime($tgl));
	    $hari=date("w",strtotime($tgl));
	    $tgl_=date("j",strtotime($tgl));
	    $bln_indo=date("n",strtotime($tgl));
        $tanggal= $nama_hari_indo[$hari].', '. $tgl_ .' '.$bln_panjang[$bln_indo].' '.$tahun;
	    return $tanggal;
    }
}
Class Generate {
    public static function Kode($length) {
        $kata='ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $code_gen = '';
        for ($i = 0; $i < $length; $i++) {
            $pos = rand(0, strlen($kata)-1);
            $code_gen .= $kata{$pos};
            }
        return $code_gen;
	}
	public static function PecahNip($nipbaru)
	{
		//nip 198203192004121002 19820319 200412 1 002
		$nip1 = substr($nipbaru,0,8);
		$nip2 = substr($nipbaru,8,6);
		$nip3 = substr($nipbaru,-4,1);
		$nip4 = substr($nipbaru,-3,3);
		$nip = $nip1.' '.$nip2.' '.$nip3.' '.$nip4;
		return $nip;
	}
}