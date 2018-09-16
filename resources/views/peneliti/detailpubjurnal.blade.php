@extends('layout.peneliti')
@section('title', 'Detail Publikasi Jurnal')
@section('styles')
<link rel="stylesheet" type="text/css" href="{{URL::asset('css/table.css')}}">
@endsection
@section('content')
<div class="container">
<div class="panel panel-default" style="border-bottom: 5px solid #2196F3;">
  <div class="panel-body">
    <h2 style="color: #2196F3;text-align: center;"> Informasi Publikasi Jurnal </h2>
	<h3 style="color: #808080;text-align: center">{{$pubjurnal->judul_artikel}}</h3>
	<hr style="border: 2px solid #2196F3">
	<div class="row">
		<div class="col-md-2">
			<h4><b>Nama Berkala Ilmiah</b></h4>
		</div>
		<div class="col-md-10">
			<h4>{{$pubjurnal->nama_berkala}}</h4>
		</div>
	</div>
	<hr>
	<div class="row">
		<div class="col-md-2">
			<h4><b>Status Akreditasi</b></h4>
		</div>
		<div class="col-md-10">
			<h4>{{$pubjurnal->status_akreditasi}}</h4>
		</div>
	</div>
	<hr>
	<div class="row">
		<div class="col-md-2">
			<h4><b>Volume/halaman</b></h4>
		</div>
		<div class="col-md-10">
			<h4>{{$pubjurnal->volume_halaman}}</h4>
		</div>
	</div>
	<hr>
	<div class="row">
		<div class="col-md-2">
			<h4><b>Tahun Terbit</b></h4>
		</div>
		<div class="col-md-10">
			<h4>{{$pubjurnal->tahun_terbit}}</h4>
		</div>
	</div>
	<hr>

	<div class="row">
		<div class="col-md-2">
			<h4><b>Peneliti</b></h4>
		</div>
		<div class="col-md-10">
			<h4>
			@foreach($penelitis as $peneliti)
				@if($peneliti->peneliti->peneliti_psb!=null)
					<span style="margin-right: 15px">{{$peneliti->peneliti->peneliti_psb->pegawai->nama}}</span>  
				@else
					<span style="margin-right: 15px">{{$peneliti->peneliti->peneliti_nonpsb->nama_peneliti}} </span>  
				@endif
			@endforeach
			</h4>
		</div>
	</div>
	<hr>

  </div>
</div>
</div>
@endsection