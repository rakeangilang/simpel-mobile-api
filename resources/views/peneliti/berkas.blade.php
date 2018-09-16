@extends('layout.peneliti')
@section('title', 'Berkas '.$tipekegiatan->nama_tipe_kegiatan)

@section('styles')
<link rel="stylesheet" type="text/css" href="{{URL::asset('css/bootstrap-select.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{URL::asset('bower_components/select2/dist/css/select2.css')}}">
@endsection
@section('content')
@include('peneliti.menu')
<div class="container">
	<ol class="breadcrumb">
		<li><a href="/"> Beranda</a></li>
		<li><a href="/vieweditkegiatan/{{$kegiatan->id}}">{{$tipekegiatan->nama_tipe_kegiatan}}</a></li>
		<li class="active">Berkas {{$tipekegiatan->nama_tipe_kegiatan}}</li>
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
						<div>
						@if($berkas == null)
							<form style="font-size: 18px" method="POST" action="{{url('/tambahBerkas/'.$kegiatan->id)}}" enctype="multipart/form-data">
								<div class="form-group">
									<label for="exampleInputEmail1">Judul Penelitian</label>
									<input type="text" class="form-control" id="" name="judul" required>
								</div>
								<div class="form-group">
									<label for="tag_list">Penulis (berafiliasi dengan Trop BRC)</label>
								<select id="psb" name="psb[]" class="form-control" multiple></select>
								</div>
								<div class="form-group">
									<label for="tag_list">Penulis (tidak berafiliasi dengan Trop BRC)</label>
									<div class="row">
										<div class="col-md-11">
											<select id="nonpsb" name="nonpsb[]" class="form-control" multiple></select>
										</div>
										<div class="col-md-1">
											<button type="button" style="margin-top: 2px" class="btn btn-primary" data-toggle="modal" data-target="#tambahnonpsb"><span class="glyphicon glyphicon-plus"></span></button>
										</div>
									</div>
								</div>
								<div class="form-group">
									@foreach($tipe_berkas as $tipeberkas)
										<label>{{$tipeberkas->nama_tipe_berkas}}</label>
										<input type="file" name="berkas{{$tipeberkas->id}}">
										<hr>
									@endforeach
								</div>
								<button id="submit" type="submit" class="btn btn-success btn-lg">Submit</button>
								{{csrf_field()}}
							</form>
						@else
							<form style="font-size: 18px" method="POST" action="{{url('/editBerkas/'.$kegiatan->id)}}" enctype="multipart/form-data">
								<input type="text" name="id_kegiatan" value="{{$kegiatan->id}}" hidden>
                     			<input type="hidden" name="_method" value="PUT">
								<div class="form-group">
									<label for="exampleInputEmail1">Judul Penelitian</label>
									<input type="text" class="form-control" id="" name="judul" value="{{$berkas->judul}}" required>
								</div>
								<div class="form-group">
									<label for="tag_list">Penulis (berafiliasi dengan Trop BRC)</label>
									
											<select id="psbedit" name="psb[]" class="form-control js-example-basic-multiple" multiple>
												@foreach($psb as $semua_psb)
													<option value="{{$semua_psb->id_peneliti}}">
														@if($penelitipsb_terpilih!=null)
															@foreach($penelitipsb_terpilih as $selected_psb)
																@if($semua_psb->id_peneliti == $selected_psb->id_peneliti)
																	<option selected="selected" value="{{$selected_psb->id_peneliti}}">{{$selected_psb->pegawai->nama}}</option>
																@endif
															@endforeach
														@endif
														{{$semua_psb->nama}}
													</option>
												@endforeach
											</select>
								</div>
								<div class="form-group">
									<label for="tag_list">Penulis (tidak berafiliasi dengan Trop BRC)</label>
									<div class="row">
										<div class="col-md-11">
											<select id="nonpsbedit" name="nonpsb[]" class="form-control js-example-basic-multiple" multiple>
												@foreach($nonpsb as $semua_nonpsb)
													<option value="{{$semua_nonpsb->id_peneliti}}">
														@if($penelitinonpsb_terpilih!=null)
															@foreach($penelitinonpsb_terpilih as $selected_nonpsb)
																@if($semua_nonpsb->id_peneliti == $selected_nonpsb->id_peneliti)
																	<option selected="selected" value="{{$selected_nonpsb->id_peneliti}}">{{$selected_nonpsb->nama_peneliti}}</option>
																@endif
															@endforeach
														@endif
														{{$semua_nonpsb->nama_peneliti}}
													</option>
												@endforeach
											</select>
										</div>
										<div class="col-md-1">
											<button type="button" style="margin-top: 2px" class="btn btn-primary" data-toggle="modal" data-target="#tambahnonpsb"><span class="glyphicon glyphicon-plus"></span></button>
										</div>
									</div>
								</div>
								<div class="form-group">

									@foreach($tipe_berkas as $index => $tipeberkas)
										@if($berkas_kegiatans[$index]!=null)
											<label><u>{{$tipeberkas->nama_tipe_berkas}}</u></label><br>
											<label class="control-label">Berkas yang sudah ada : </label><br>
											<label style="color: #1c7bd9;">{{$berkas_kegiatans[$index]->nama_berkas}}</label><br>
											<input id="fileUpload" type="file" name="berkas{{$tipeberkas->id}}" onchange="ValidateExtension()">
											<span id="lblError" style="color: red"></span>
										@else
											<label><u>{{$tipeberkas->nama_tipe_berkas}}</u></label>
											<input id="fileUpload" type="file" name="berkas{{$tipeberkas->id}}" onchange="ValidateExtension()">
											<span id="lblError" style="color: red"></span>
										@endif
										<hr>
								
									@endforeach
								</div>
								<button id="submit" type="submit" class="btn btn-success btn-lg">Simpan</button>
								{{csrf_field()}}
							</form>
						@endif	
						</div>
					</div>
				</div>
				</div>
			</div>
			<div class="col-md-3 hidden-xs">
				<div class="panel panel-default">
					<div class="panel-body" style="text-align: center">
						<div style="text-align: center">
							<img src="{{asset('img/contract.svg')}}" style="width: 50px;height: 50px">
						</div>
						<div>
							<h3 style="color: #1c7bd9"><b>Arsip berkas yang diperlukan untuk kegiatan {{strtolower($tipekegiatan->nama_tipe_kegiatan)}}</b></h3>
						</div>
					</div>
				</div>
			</div>
	</div>
</div>


<!-- Modal Tambah Non PSB-->
<div class="modal fade" id="tambahnonpsb" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" style="color: #ffffff;text-align: center" id="myModalLabel"><b>Tambah Peneliti Non PSB</b></h4>
      </div>
      <div class="modal-body">
       	<form method="POST" action="{{url('/tambahNonPSB')}}">
       	{{csrf_field()}}
		<div class="form-group">
			<label for="exampleInputPassword1">Nama Peneliti</label>
			<input type="text" class="form-control" id="nama" name="nama" placeholder="Nama Peneliti">
		</div>
		<div class="form-group">
			<label for="exampleInputPassword1">Nomor Identitas</label>
			<input type="text" class="form-control" id="nomor" name="nomor" placeholder="Nomor Identitas">
		</div>
		<div class="form-group">
			<label for="exampleInputEmail1">Tipe Identitas</label>
			<select class="form-control" name="tipe_identitas">
			  <option value="NIP">NIP</option>
			  <option value="KTP">KTP</option>
			</select>
		</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save changes</button>
        </form>
      </div>
    </div>
  </div>
</div>

@endsection
@section('script')
<script type="text/javascript" src="{{URL::asset('bower_components/select2/dist/js/select2.full.js')}}"></script>
<script>
	function ValidateExtension() {
        var allowedFiles = [".pdf",".doc",".docx"];
        var fileUpload = document.getElementById("fileUpload");
        var lblError = document.getElementById("lblError");
        var regex = new RegExp("([a-zA-Z0-9\s_\\.\-:])+(" + allowedFiles.join('|') + ")$");
        if (!regex.test(fileUpload.value.toLowerCase()) && fileUpload.value!="") {
            lblError.innerHTML = "Silahkan masukkan format file: <b>" + allowedFiles.join(', ') + "</b>.";
            document.getElementById("submit").setAttribute("disabled","disabled");
            return false;
        }
        lblError.innerHTML = "";
            document.getElementById("submit").removeAttribute("disabled");
            return true;
    }

    $('#psb').select2({
        placeholder: "Cari peneliti...",
        ajax: {
            url: '/penulispsb/find',
            dataType: 'json',
            data: function (params) {
                return {
                    q: $.trim(params.term)
                };
            },
            processResults: function (data) {
                return {
                    results: data
                };
            },
            cache: false
        }
    });
</script>
<script>
    $('#nonpsb').select2({
	    placeholder: "Cari peneliti...",
	    ajax: {
	        url: '/penulisnonpsb/find',
	        dataType: 'json',
	        data: function (params) {
	            return {
	                q: $.trim(params.term)
	            };
	        },
	        processResults: function (data) {
	            return {
	                results: data
	            };
	        },
	        cache: false
	    }
	});
</script>
<script>
     $('#psbedit').select2({
	    placeholder: "Cari peneliti...",

	});
</script>

<script>
     $('#nonpsbedit').select2({
	    placeholder: "Cari peneliti...",

	});
</script>

<script type="text/javascript">

	@if(Session::has('msg'))
      swal("{{ Session::get('title')}}","{{ Session::get('msg')}}","{{ Session::get('alert-type')}}");
  	@endif

</script>


@endsection