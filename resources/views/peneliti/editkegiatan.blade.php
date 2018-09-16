@extends('layout.peneliti')
@section('title', 'Edit Kegiatan')
@section('breadcrumb', 'Seminar Ilmiah')
@section('content')
<div class="container">
	<ol class="breadcrumb" style="font-size: 20px">
		<li><a href="/"></i> Beranda</a></li>
		<li class="active">{{$tipekegiatan->nama_tipe_kegiatan}}</li>
	</ol>
	
	<div class="row">
		<div class="col-md-9">
			<div class="panel panel-default">
				<div class="container-fluid">
					<div class="panel-body">
						<div style="text-align: center">
							<h3 style="color: #1c7bd9"><b>{{$kegiatan->nama_kegiatan}}</h3>						
						</div>
						<hr style="border: 2px solid #1c7bd9">

							<form style="font-size: 18px" method="POST" action="{{url('/editKegiatan/'.$kegiatan->id)}}" enctype="multipart/form-data">
								<input type="text" name="id_mat" value="{{$kegiatan->id}}" hidden>
								<input type="hidden" name="_method" value="PUT">
								<div class="form-group">
									<label for="exampleInputEmail1">Nama Kegiatan</label>
									<input type="text" class="form-control" id="" name="nama" value="{{$kegiatan->nama_kegiatan}}">
								</div>
								<div class="form-group">
									<label for="exampleInputEmail1">Kategori {{$tipekegiatan->nama_tipe_kegiatan}}</label>
									<select class="form-control" name="kategori">
									  @foreach($kategoris as $kategori)
									  	<option @if($kategori_terpilih->id_kategori_tipe_kegiatan==$kategori->id) selected="selected" @endif value="{{$kategori->id}}">{{$kategori->keterangan}}</option>
									  @endforeach
									</select>
								</div>
								<div class="form-group">
									<label for="exampleInputEmail1">Peran dalam {{$tipekegiatan->nama_tipe_kegiatan}}</label>
									<select class="form-control" name="peran">
									  @foreach($perans as $peran)
									  	<option @if($peran_terpilih->id_peran==$peran->id) selected="selected" @endif value="{{$peran->id}}">{{$peran->nama_peran}}</option>
									  @endforeach
									</select>
								</div>
								
								@if($tipekegiatan->dokumentasi == 'ya')
									<div class="form-group">
										<label for="exampleInputEmail1">Foto Kegiatan</label><br>
										@if($berkas!=null)
											<label class="control-label">Foto yang sudah diupload : </label><br>
      										<img src="{{asset($tipekegiatan->nama_tipe_kegiatan.'/'.$kegiatan->id.'/foto/'.$berkas->nama_berkas)}}" style="width: 50px;height: 50px; margin-right: 10px;margin-bottom: 15px">
      										<label style="white-space: normal">{{$berkas->nama_berkas}}</label>
										<input type="file" name="foto" >
										@else
											<input type="file" name="foto">
										@endif
									</div>
									<div class="form-group">
										<label for="exampleInputEmail1">Keterangan Kegiatan</label>
										<input type="text" class="form-control" name="keterangan" value="{{$kegiatan->keterangan}}">

									</div>
									<div class="form-group">
										<label for="exampleInputEmail1">Lokasi Kegiatan</label>
										<input type="text" class="form-control" id="" name="lokasi" value="{{$kegiatan->lokasi}}">
									</div>
								@endif

								@if($kegiatan->id_tipe_kegiatan==2)
								<div class="form-group">
									<label for="">Instansi</label>
									<input type="text" class="form-control" name="instansi" value="{{$kegiatan->instansi}}">
								</div>
								@endif
				
								<div class="form-group">
									<div class="row">
										<div class="col-md-6">
											<label for="exampleInputEmail1">Tanggal Awal</label>
											<input type="date" class="form-control" id="" name="tglawal" value="{{$kegiatan->tanggal_awal}}">
										</div>
										<div class="col-md-6">
											<label for="exampleInputEmail1">Tanggal Akhir</label>
											<input type="date" class="form-control" id="" name="tglakhir" value="{{$kegiatan->tanggal_akhir}}">
										</div>
									</div>
								</div>
								<button id="submit" type="submit" class="btn btn-success btn-lg">Simpan</button>
								{{csrf_field()}}
							</form>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-3 hidden-xs">
			<div class="panel panel-default">
				<div class="panel-body">
					<div style="text-align: center">
						<img src="{{asset('img/contract.svg')}}" style="width: 50px;height: 50px">
					</div>
					<div style="text-align: center">
						<h3 style="color: #1c7bd9"><b>Ubah data yang diperlukan untuk  kegiatan {{strtolower($tipekegiatan->nama_tipe_kegiatan)}}</b></h3>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
@section('script')
<script type="text/javascript">
	@if(Session::has('msg'))
swal("{{ Session::get('title')}}","{{ Session::get('msg')}}","{{ Session::get('alert-type')}}");
	@endif
</script>
@endsection