<?php
/**
  * Rubah format tanggal ke format indonesia dengan nama bulan dan hari indonesia
  * @param  string $timestamp   [bisa dalam bentuk timestamp atau unix_date]
  * @param  string $date_format [d F Y ==> 12 Januari 2017]
  * @param  string $suffix      [contoh tuliskan WIB default false]
  * @return [string]              [tanggal indonesia]
  *
  * created by navotera@gmail.com
  * share-system.com
  */
  
  function convertbulan($bulan){

    switch ($bulan) {
      case 'Januari':
        $v1 = '01';
        break;
      case 'Februari':
       $v1 = '02';
        break;
      case 'Maret':
        $v1 = '03';
        break;
      case 'April':
       $v1 = '04';
        break;
      case 'Mei':
        $v1 = '05';
        break;
      case 'Juni':
       $v1 = '06';
        break;
      case 'Juli':
       $v1 = '07';
        break;
      case 'Agustus':
        $v1 = '08';
        break;
      case 'September':
       $v1 = '09';
        break;
      case 'Oktober':
        $v1 = '10';
        break;
      case 'November':
       $v1 = '11';
        break;
      case 'Desember':
       $v1 = '12';
        break;
      default:
        $v1 = '07';
        break;
    }

    return $v1;
  }
  
  function indonesian_date ($timestamp = '', $date_format = 'd F Y', $suffix = '') {
    if($timestamp == NULL)
      return '-';

    if($timestamp == '1970-01-01' || $timestamp == '0000-00-00' || $timestamp == '-25200')
      return '-';


    if (trim ($timestamp) == '')
    {
            $timestamp = time ();
    }
    elseif (!ctype_digit ($timestamp))
    {
        $timestamp = strtotime ($timestamp);
    }
    # remove S (st,nd,rd,th) there are no such things in indonesia :p
    $date_format = preg_replace ("/S/", "", $date_format);
    $pattern = array (
        '/Mon[^day]/','/Tue[^sday]/','/Wed[^nesday]/','/Thu[^rsday]/',
        '/Fri[^day]/','/Sat[^urday]/','/Sun[^day]/','/Monday/','/Tuesday/',
        '/Wednesday/','/Thursday/','/Friday/','/Saturday/','/Sunday/',
        '/Jan[^uary]/','/Feb[^ruary]/','/Mar[^ch]/','/Apr[^il]/','/May/',
        '/Jun[^e]/','/Jul[^y]/','/Aug[^ust]/','/Sep[^tember]/','/Oct[^ober]/',
        '/Nov[^ember]/','/Dec[^ember]/','/Januari/','/Februari/','/Maret/','/April/','/Juni/','/Juli/','/Agustus/','/September/',
        '/Oktober/','/November/','/Desember/',
    );
    $replace = array ( 'Sen','Sel','Rab','Kam','Jum','Sab','Min',
        'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu',
        'Jan','Feb','Mar','Apr','Mei','Jun','Jul','Ags','Sep','Okt','Nov','Des',
        '1','Februari','Maret','April','Juni','Juli','Agustus','September',
        'Oktober','November','Desember',
    );
    $date = date ($date_format, $timestamp);
    $date = preg_replace ($pattern, $replace, $date);
    $date = "{$date} {$suffix}";
    return $date;
} 

?>