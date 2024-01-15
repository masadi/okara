@extends('layouts.cetak')
@section('content')
  <table border="0" width="100%">
    <tr>
      <td width="15%" rowspan="4">
        <img src="{{($get_siswa->rombongan_belajar->sekolah && $get_siswa->rombongan_belajar->sekolah->logo_sekolah) ? public_path('/storage'.config('erapor.storage').'/images/'.$get_siswa->rombongan_belajar->sekolah->logo_sekolah) : public_path('/images/logo/logo.png')}}" height="75" />
      </td>
      <td width="90%" class="text-center" colspan="2" style="padding: 0px;">
        <strong style="font-size: 13px;">YAYASAN DARUL KAROMAH</strong>
      </td>
    </tr>
    <tr>
      <td colspan="2" class="text-center" style="padding: 0px;"><strong style="font-size: 20px;">{{$get_siswa->rombongan_belajar->sekolah->nama}}</strong></td>
    </tr>
    <tr>
      <td class="text-right" style="padding: -5px 15px -5px 0px;"><strong style="font-size: 10px;">NSS. {{$get_siswa->rombongan_belajar->sekolah->nss}}</strong></td>
      <td style="padding: 0px 0px 0px 15px;"><strong style="font-size: 10px;">NPSN. {{$get_siswa->rombongan_belajar->sekolah->npsn}}</strong></td>
    </tr>
    <tr>
      <td colspan="2" class="text-center" style="padding: 0px"><strong style="font-size: 10px;">AKREDITASI B - No. SK: 133/BAN-S/M.35/SK/X/2018</strong></td>
    </tr>
    <tr>
      <td colspan="3" class="text-center" style="background: black"><strong style="font-size: 9px; color:white">Alamat: Kompleks Ponpes Darul Karomah Bicabbi 1 Larangan Luar Larangan Pamekasan Kode Pos 69384</strong></td>
    </tr>
  </table>
  <p>&nbsp;</p>
  <p class="text-center">
    <strong style="font-size: 13px"><u>SURAT KETERANGAN LULUS</u></strong> <br>
<strong>PEMINATAN ILMU PENGETAHUAN SOSIAL<br>
TAHUN PELAJARAN 2022/2023</strong><br>
Nomor: 216/SMAI.DK/V/2023<br>
</p>
<p class="text-justify">
Yang bertanda tangan di bawah ini, Kepala Sekolah {{$get_siswa->rombongan_belajar->sekolah->nama}} menerangkan bahwa:
</p>
<table border="0" width="100%">
	<tr>
    	<td style="width: 30%;padding:0px;">Nama</td>
      <td style="width: 70%">: {{strtoupper($get_siswa->peserta_didik->nama)}}</td>
	</tr>
  <tr>
		<td style="padding:0px;">Tempat/Tanggal Lahir</td>
		<td>: {{ucwords(strtolower($get_siswa->peserta_didik->tempat_lahir)).', '.$get_siswa->peserta_didik->tanggal_lahir}}</td>
	</tr>
	<tr>
		<td style="padding:0px;">Nomor Induk Siswa</td>
		<td>: {{$get_siswa->peserta_didik->no_induk}}</td>
	</tr>
  <tr>
		<td style="padding:0px;">Nomor Induk Siswa Nasional</td>
		<td>: {{$get_siswa->peserta_didik->nisn}}</td>
	</tr>
</table>
<p>berdasarkan kriteria kelulusan yang sudah ditetapkan, maka yang bersangkutan dinyatakan:</p>
<p class="text-center"><strong>LULUS</strong></p>
<p class="text-justify">
dengan hasil sebagai berikut
</p>
  <table class="table table-bordered" border="1">
    <thead>
      <tr>
        <th style="vertical-align:middle;" class="text-center" width="10%">No</th>
        <th style="vertical-align:middle;" class="text-center" width="70%" >Mata Pelajaran<br>{{$get_siswa->rombongan_belajar->kurikulum->nama_kurikulum}}</th>
        <th style="vertical-align:middle;" class="text-center" width="20%" >Nilai Ujian Sekolah</th>
      </tr>
    </thead>
    <tbody>
    <?php
    $rerata = [];
    $all_pembelajaran = array();
    $all_pembelajaran_pilihan = array();
    $get_pembelajaran = [];
    $get_pembelajaran_pilihan = [];
    $set_pembelajaran = $get_siswa->rombongan_belajar->pembelajaran;
    foreach($set_pembelajaran as $pembelajaran){
      if(in_array($pembelajaran->mata_pelajaran_id, mapel_agama())){
        if(filter_pembelajaran_agama($get_siswa->peserta_didik->agama->nama, $pembelajaran->mata_pelajaran->nama)){
          $get_pembelajaran[$pembelajaran->pembelajaran_id] = $pembelajaran;
        }
      } else {
        $get_pembelajaran[$pembelajaran->pembelajaran_id] = $pembelajaran;
      }
    }
    ?>
    @foreach($get_pembelajaran as $pembelajaran)
    <?php
    if(merdeka($get_siswa->rombongan_belajar->kurikulum->nama_kurikulum)){
      $nilai_akhir = ($pembelajaran->nilai_akhir_kurmer) ? number_format($pembelajaran->nilai_akhir_kurmer->nilai, 0) : 0;
    } else {
      $nilai_akhir = ($pembelajaran->nilai_akhir_pengetahuan) ? number_format($pembelajaran->nilai_akhir_pengetahuan->nilai,0) : 0;
    }
    $produktif = array(4,5,9,10,13);
    if(in_array($pembelajaran->kelompok_id,$produktif)){
      $produktif = 1;
    } else {
      $produktif = 0;
    }
    $all_pembelajaran[$pembelajaran->kelompok->nama_kelompok][] = array(
      'nama_mata_pelajaran'	=> $pembelajaran->nama_mata_pelajaran,
      'nilai_akhir'	=> $nilai_akhir,
    );
    $i=1;
    ?>
    @endforeach
    @foreach($all_pembelajaran as $kelompok => $data_pembelajaran)
    @if($kelompok == 'C1. Dasar Bidang Keahlian' || $kelompok == 'C3. Kompetensi Keahlian')
    <tr>
      <td colspan="3" class="strong"><strong style="font-size: 13px;">C. Muatan Peminatan Kejuruan</strong></td>
    </tr>
    @endif
    <tr>
      <td colspan="3" class="strong"><strong style="font-size: 13px;">{{$kelompok}}</strong></td>
    </tr>
    @foreach($data_pembelajaran as $pembelajaran)
    <?php 
    $pembelajaran = (object) $pembelajaran; 
    $rerata[] = $pembelajaran->nilai_akhir;
    ?>
      <tr>
        <td class="text-center" style="vertical-align:middle;">{{$i++}}</td>
        <td style="vertical-align:middle;">{{$pembelajaran->nama_mata_pelajaran}}</td>
        <td class="text-center" style="vertical-align:middle;">{{$pembelajaran->nilai_akhir}}</td>
      </tr>
    @endforeach
    @endforeach
    <tr>
      <td class="text-center" colspan="2"><strong>Rata-rata</strong></td>
      <td class="text-center"><strong>{{number_format(collect($rerata)->avg(), '2', '.', '.')}}</strong></td>
    </tr>
    </tbody>
  </table>
  <table width="100%">
    <tr>
      <td style="width:70%">&nbsp;</td>
      <td style="width:30%"><p style="font-size: 12px;">{{$get_siswa->peserta_didik->sekolah->kabupaten}}, {{$tanggal_rapor}}<br>Kepala Sekolah</p><br>
    <br>
    <br>
    <br>
    <p><u><strong>{{$get_siswa->peserta_didik->sekolah->kepala_sekolah->nama_lengkap}}</strong></u><br />
        NIP. {{$get_siswa->peserta_didik->sekolah->kepala_sekolah->nip}}
    </p>
    </td>
    </tr>
  </table>
@endsection
