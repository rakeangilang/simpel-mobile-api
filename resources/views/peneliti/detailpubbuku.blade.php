@extends('layout.peneliti')
@section('title', 'Detail Publikasi Buku')
@section('styles')
<link rel="stylesheet" type="text/css" href="{{URL::asset('css/table.css')}}">
@endsection
@section('content')
<div class="container">
<div class="panel panel-default" style="border-bottom: 5px solid #2196F3;">
  <div class="panel-body">
    <h2 style="color: #2196F3;text-align: center;"> Informasi Publikasi Buku </h2>
	<h3 style="color: #808080;text-align: center">{{$pubbuku->judul_buku}}</h3>
	<hr style="border: 2px solid #2196F3">
	<div class="row">
		<div class="col-md-2">
			<h4><b>Judul Book Chapter</b></h4>
		</div>
		<div class="col-md-10">
			<h4>{{$pubbuku->judul_book_chapter}}</h4>
		</div>
	</div>
	<hr>
	<div class="row">
		<div class="col-md-2">
			<h4><b>Nama Penerbit</b></h4>
		</div>
		<div class="col-md-10">
			<h4>{{$pubjurnal->nama_penerbit}}</h4>
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
			<h4><b>ISBN</b></h4>
		</div>
		<div class="col-md-10">
			<h4>{{$pubjurnal->isbn}}</h4>
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