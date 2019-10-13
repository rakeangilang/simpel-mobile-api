@extends('layout.peneliti')
@section('title', 'Publikasi Jurnal')
@section('breadcrumb', 'Publikasi')
@section('styles')
<link rel="stylesheet" type="text/css" href="{{URL::asset('bower_components/select2/dist/css/select2.css')}}">
@endsection
@section('content')
<div class="container">
	<ol class="breadcrumb" style="font-size: 20px">
		<li><a href="/"></i> Beranda</a></li>
		<li class="active">Publikasi Jurnal</li>
	</ol>
	<div class="row">
		<div class="col-md-9">
			<div class="panel panel-default">
				<div class="container-fluid">
					<div class="panel-body">
						<div style="text-align: center">
							<h3 style="color: #1c7bd9"><b>Form Publikasi Jurnal</b></h3>
						</div>
						<hr>
						<form style="font-size: 18px" method="POST" action="{{url('/tambahpubjurnal')}}"> 
							<div class="form-group">
								<label for="exampleInputEmail1">Judul Artikel</label>
								<input type="text" class="form-control" id="" name="judul">
							</div>
							<div class="form-group">
								<label for="tag_list">Penulis (berafiliasi dengan Trop BRC)</label>
							<select id="psb" name="psb[]" class="form-control" multiple></select>
							</div>
							<div class="form-group">
								<label for="tag_list">Penulis (tidak berafiliasi dengan Trop BRC)</label>
								<div class="row">
									<div class="col-md-11 col-sm-11 col-xs-12">
										<select id="nonpsb" name="nonpsb[]" class="form-control" multiple></select>
									</div>
									<div class="col-md-1 col-sm-1 col-xs-1">
										<button type="button" style="margin-top: 2px" class="btn btn-primary" data-toggle="modal" data-target="#tambahnonpsb"><span class="glyphicon glyphicon-plus"></span></button>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label for="exampleInputEmail1">Status Akreditasi</label>
								<select class="form-control" name="status_akreditasi">
								  <option value="Terindeks database internasional(Web of Science, Scopus)">Terindeks database internasional(Web of Science, Scopus)</option>
								  <option value="Terakreditasi Nasional A">Terakreditasi Nasional A</option>
								  <option value="Terakreditasi Nasional B">Terakreditasi Nasional B</option>
								  <option value="Terakreditasi Nasional C">Terakreditasi Nasional C</option>
								  <option value="Tidak Terakreditasi">Tidak Terakreditasi</option>
								</select>
							</div>
							<div class="form-group">
								<label for="exampleInputEmail1">Nama Berkala</label>
								<input type="text" class="form-control" id="" name="namaberkala">
							</div>
							<div class="form-group">
								<label for="exampleInputEmail1">Volume dan Halaman</label>
								<input type="text" class="form-control" id="" name="volume">
							</div>
							<div class="form-group">
								<label for="exampleInputEmail1">URL</label>
								<input type="text" class="form-control" id="" name="url">
							</div>
							<div class="form-group">
								<label for="exampleInputEmail1">Tahun Terbit</label>
								<input type="text" class="form-control" id="tahun" name="tahun" oninput="validasitahun()">
								<span id="alerttahun" class="text-danger"></span>
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
						<h3 style="color: #1c7bd9"><b>Tambah data jurnal yang telah terpublikasi</b></h3>
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
        	<h3 class="modal-title" id="myModalLabel" style="color: #ffffff;text-align: center"><b>Tambah Peneliti NonPSB</b>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class="fas fa-times"></i></span></button>
	        </h3>
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

<script type="text/javascript">
	function validasitahun(){
		if(document.getElementById("tahun").value!="" && document.getElementById("tahun").value.search(/[a-z ~ ` ! @ # $ % ^ & * ( ) _ - + = | \ / ' ' " " ; : ? > . < ,]/g) !== -1 || document.getElementById("tahun").value!="" && document.getElementById("tahun").value == ""){
			document.getElementById("alerttahun").innerHTML = "Isi dengan angka";
		}
		else{
			document.getElementById("alerttahun").innerHTML = "";
		}
	}
</script>
@endsection