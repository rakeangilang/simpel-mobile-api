@extends('layout.peneliti')
@section('title', 'Detail Kegiatan')
@section('styles')
<link rel="stylesheet" type="text/css" href="{{URL::asset('css/table.css')}}">
@endsection
@section('content')
<div class="container">
<div class="panel panel-default">
  <div class="panel-body">
    <h2 style="color: #448aff;text-align: center;"> Informasi Kegiatan </h2>
	<h3 style="color: #808080;text-align: center">{{$kegiatan->nama_kegiatan}}</h3>
	<hr style="border: 2px solid #448aff">
	<div class="row">
		<div class="col-md-2">
			<h4><b>Tipe Kegiatan</b></h4>
		</div>
		<div class="col-md-10">
			<h4>{{$kegiatan->nama_tipe_kegiatan}}</h4>
		</div>
	</div>
	<hr>

	<div class="row">
		<div class="col-md-2">
			<h4><b>Judul Penelitian</b></h4>
		</div>
		@if($berkas!=null)
		<div class="col-md-10">
			<h4>{{$berkas->judul}}</h4>
		</div>
		@else
		<div class="col-md-10">
			<h4> - </h4>
		</div>
		@endif
	</div>
	<hr>
	
	@if($kegiatan->lokasi!=null)
	<div class="row">
		<div class="col-md-2">
			<h4><b>Lokasi</b></h4>
		</div>
		<div class="col-md-10">
			<h4>{{$kegiatan->lokasi}}</h4>
		</div>
	</div>
	<hr>
	@endif

	@if($kegiatan->instansi!=null)
	<div class="row">
		<div class="col-md-2">
			<h4><b>Instansi</b></h4>
		</div>
		<div class="col-md-10">
			<h4>{{$kegiatan->instansi}}</h4>
		</div>
	</div>
	<hr>
	@endif

	<div class="row">
		<div class="col-md-2">
			<h4><b>Tanggal </b></h4>
		</div>
		<div class="col-md-10">
			<h4>
				<span style="margin-right: 15px">{{Carbon\Carbon::parse($kegiatan->tanggal_awal)->format('d F Y')}}</span> - 
				<span style="margin-left: 15px">{{Carbon\Carbon::parse($kegiatan->tanggal_akhir)->format('d F Y')}}</span>
			</h4>
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