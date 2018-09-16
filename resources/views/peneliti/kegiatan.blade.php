@extends('layout.peneliti')
@section('title', 'Kegiatan')
@section('breadcrumb', 'Seminar Ilmiah')
@section('content')
<div class="container">
	<ol class="breadcrumb" style="font-size: 20px">
		@foreach($tipekegiatans as $tipekegiatan)
		@if($id==$tipekegiatan->id)
		<li><a href="/"></i> Beranda</a></li>
		<li class="active">{{$tipekegiatan->nama_tipe_kegiatan}}</li>
		@endif
		@endforeach
	</ol>
	
	<div class="row">
		<div class="col-md-9">
			<div class="panel panel-default">
				<div class="container-fluid">
					<div class="panel-body">
						
						<div style="text-align: center">
							@foreach($tipekegiatans as $tipekegiatan)
								@if($id==$tipekegiatan->id)
									<h3 style="color: #1c7bd9"><b>Form {{$tipekegiatan->nama_tipe_kegiatan}}</b></h3>
								@endif
							@endforeach

						</div>
					
						<hr>
						<form style="font-size: 18px" method="POST" action="{{url('/tambahKegiatan/'.$id)}}" enctype="multipart/form-data">
							{{-- nama kegiatan --}}
							<div class="form-group">
								@foreach($tipekegiatans as $tipekegiatan)
									@if($id==$tipekegiatan->id)
										<label for="exampleInputEmail1">Nama Kegiatan {{$tipekegiatan->nama_tipe_kegiatan}}</label>
									@endif
								@endforeach
								<input type="text" class="form-control" id="" name="nama" required>
							</div>
							{{-- kategori kegiatan --}}
							<div class="form-group">
								@foreach($tipekegiatans as $tipekegiatan)
									@if($id==$tipekegiatan->id)
										<label for="exampleInputEmail1">Kategori {{$tipekegiatan->nama_tipe_kegiatan}}</label>
									@endif
								@endforeach
								<select class="form-control" name="kategori" required>
								  @foreach($kategoris as $kategori)
								  	<option value="{{$kategori->id}}">{{$kategori->keterangan}}</option>
								  @endforeach
								</select>
							</div>
							{{-- peran dlm kegiatan --}}
							<div class="form-group">
								@foreach($tipekegiatans as $tipekegiatan)
									@if($id==$tipekegiatan->id)
										<label for="exampleInputEmail1">Peran dalam {{$tipekegiatan->nama_tipe_kegiatan}}</label>
									@endif
								@endforeach
								<select class="form-control" name="peran" required>
								  @foreach($perans as $peran)
								  	<option value="{{$peran->id}}">{{$peran->nama_peran}}</option>
								  @endforeach
								</select>
							</div>

							{{-- jika dalam kegiatan terdapat foto dokumentasi --}}
							@foreach($tipekegiatans as $tipekegiatan)
								@if($id==$tipekegiatan->id)		
									@if($tipekegiatan->dokumentasi == 'ya')
										<div class="form-group">
											<label for="">Foto Kegiatan</label>
											<input type="file" name="foto">
										</div>
										<div class="form-group">
											<label for="">Keterangan Kegiatan</label>
											<input type="text" class="form-control" name="keterangan">
										</div>
										<div class="form-group">
											<label for="">Lokasi Kegiatan</label>
											<input type="text" class="form-control" id="" name="lokasi">
										</div>
									@endif
								@endif
							@endforeach

							{{-- jika kegiatannya adalah kerjasama --}}
							@if($id==2)
								<div class="form-group">
									<label for="">Instansi</label>
									<input type="text" class="form-control" name="instansi">
								</div>
							@endif
							
							{{-- tanggal kegiatan --}}
							<div class="form-group">
								<div class="row">
									<div class="col-md-6">
										<label for="exampleInputEmail1">Tanggal Awal</label>
										<input type="date" class="form-control" id="" name="tglawal">
									</div>
									<div class="col-md-6">
										<label for="exampleInputEmail1">Tanggal Akhir</label>
										<input type="date" class="form-control" id="" name="tglakhir">
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
				<div class="panel-body" style="text-align: center">
					<div>
						<img src="{{asset('img/contract.svg')}}" style="width: 50px;height: 50px">
					</div>
					<div>
						@foreach($tipekegiatans as $tipekegiatan)
								@if($id==$tipekegiatan->id)
									<h3 style="color: #1c7bd9"><b>Tambah data kegiatan {{strtolower($tipekegiatan->nama_tipe_kegiatan)}}</b></h3>
								@endif
						@endforeach
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