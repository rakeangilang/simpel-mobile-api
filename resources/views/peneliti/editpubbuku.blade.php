@extends('layout.peneliti')
@section('title', 'Publikasi Buku')
@section('breadcrumb', 'Publikasi')
@section('styles')
<link rel="stylesheet" type="text/css" href="{{URL::asset('bower_components/select2/dist/css/select2.css')}}">
@endsection
@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-9">
			<div class="panel panel-default">
				<div class="container-fluid">
					<div class="panel-body">
						<div class="row">
							<div class="col-md-1 col-xs-4">
								<img src="{{asset('img/contract.svg')}}" style="width: 50px;height: 50px">
								
							</div>
							<div class="col-md-9 col-xs-8" style="margin-left: -20px">
								<h3 style="color: #1c7bd9"><b>Tambah Book Chapter Anda yang Telah Terpublikasi Internasional</b></h3>
							</div>
						</div>
						<hr>
						<form style="font-size: 18px" method="POST" action="{{url('/editPubbuku')}}"> 
							<input type="text" name="id_pubbuku" value="{{$pubbuku->id}}" hidden>
					        <input name="_method" type="hidden" value="PUT">
							<div class="form-group">
								<label for="exampleInputEmail1">Judul Buku</label>
								<input type="text" class="form-control" id="" name="judulbuku" value="{{$pubbuku->judul_buku}}">
							</div>
							<div class="form-group">
								<label for="exampleInputEmail1">Judul Book Chapter</label>
								<input type="text" class="form-control" id="" value="{{$pubbuku->judul_book_chapter}}" name="judulchapter">
							</div>
							<div class="form-group">
								<label for="tag_list">Penulis (berafiliasi dengan Trop BRC)</label>
								<div class="row">
									<div class="col-md-11">
										<select id="psb" name="psb[]" class="form-control" multiple>
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
									<div class="col-md-1">
										<button type="button" style="margin-top: 2px" class="btn btn-primary" data-toggle="modal" data-target="#tambahnonpsb"><span class="glyphicon glyphicon-plus"></span></button>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label for="tag_list">Penulis (tidak berafiliasi dengan Trop BRC)</label>
							<select id="nonpsb" name="nonpsb[]" class="form-control" multiple>
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
							<div class="form-group">
								<label for="exampleInputEmail1">Tahun Terbit</label>
								<input type="text" class="form-control" id="tahun" name="tahunterbit" value="{{$pubbuku->tahun_terbit}}" oninput="validasitahun()">
								<span id="alerttahun" class="text-danger"></span>
							</div>
							<div class="form-group">
								<label for="exampleInputEmail1">Nama Penerbit</label>
								<input type="text" class="form-control" id="" name="namapenerbit" value="{{$pubbuku->nama_penerbit}}">
							</div>
							<div class="form-group">
								<label for="exampleInputEmail1">ISBN</label>
								<input type="text" class="form-control" id="" name="isbn" value="{{$pubbuku->isbn}}">
							</div>
							<button id="submit" type="submit" class="btn btn-success btn-lg">Simpan</button>
							{{csrf_field()}}
						</form>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-3">
			<div class="panel panel-default">
				<div class="panel-body">
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
        <h4 class="modal-title" id="myModalLabel">Tambah Peneliti Non PSB</h4>
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

	});
</script>

<script>
     $('#nonpsb').select2({
	    placeholder: "Cari peneliti...",
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
