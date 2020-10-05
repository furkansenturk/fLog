<?php 

class flog { 
    private $kullaniciAdi;
    private $sayfa;
    private $klasorAdresi;
    public function __construct($klasorAdresi){
        $this->klasorAdresi = $klasorAdresi;
        $this->kullaniciAdi = null;
		$this->sifirla();
    }
    private function sifirla(){
        $this->sayfa = null;
    }
    private function dosyaAdresi($x){
        $klasor = $this->klasorAdresi;
        $klasorisim = date('Y-m');
        if(!file_exists("$klasor/$klasorisim")){
            mkdir("$klasor/$klasorisim");	
        }
        return "$klasor/$klasorisim/$x-Log.txt";
    }
    public function kullanici($x){
        $this->kullaniciAdi = $x;
    }
    public function yazdir($x){
        $kadi = $this->kullaniciAdi;
        $ip = $this->getIP();
        if($kadi == null){
            $kayitdosyasi = $this->dosyaAdresi("FLog-TANIMSIZ");
            $yazi = "[".date('Y-m-d H:i:s')."] ";
        }else{
            $kayitdosyasi = $this->dosyaAdresi($kadi);
            $yazi = "[".date('Y-m-d H:i:s')." - ".$kadi."] ";
        }
        $yazi.=  "[".$ip."] $x\n";
        $ydosya  = fopen ($kayitdosyasi , "aw");
        fwrite($ydosya,$yazi);
		fclose($ydosya);

        $this->sifirla();
    }
    private function getIP(){
        if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
            $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
            $_SERVER['HTTP_CLIENT_IP'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
        }
        $client  = @$_SERVER['HTTP_CLIENT_IP'];
        $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
        $remote  = $_SERVER['REMOTE_ADDR'];
        if(filter_var($client, FILTER_VALIDATE_IP)){
            $ip = $client;
        }elseif(filter_var($forward, FILTER_VALIDATE_IP)){
            $ip = $forward;
        }else{
            $ip = $remote;
        }
        return $ip;
    }
    public function kayitlar($data,$tarih){
        $klasor = $this->klasorAdresi;
		if(file_exists("$klasor/$tarih/$data-Log.txt")){
		 $loglar = file_get_contents("$klasor/$tarih/$data-Log.txt");
		}else{
			$loglar = false ; 
		}
	    return $loglar ;
    }
    public function tarihListesi(){
        $klasor = $this->klasorAdresi;
        $klasorler = scandir($klasor);
        $klasorler = \array_diff($klasorler, [".", ".."]);
        sort($klasorler);
        return $klasorler;

    }
    
}
?>
