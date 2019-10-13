@extends('layout.peneliti')
@section('title', 'penggunaan dana')
@section('styles')

@endsection
@section('content')
<div class="container">
<ol class="breadcrumb" style="font-size: 20px">
	<li><a href="/"></i> Beranda</a></li>
	<li class="active">Pengajuan Dana Kegiatan</li>
</ol>
<div class="row">
	<div class="col-md-9 col-xs-12">
		<div class="panel panel-default">
			<div class="container-fluid">
				<div class="panel-body">
					<div style="text-align: center">
						<h3 style="color: #1c7bd9"><b>Form Pengajuan Dana</b></h3>
					</div>
					<hr>
					<form style="font-size: 18px" method="POST" action="{{url('/mengajukanDana')}}"> 
						<div class="form-group">
							<label for="exampleInputEmail1">Pilih Kegiatan</label>
							<select class="form-control" name="id_kegiatan">
							  	@foreach($kegiatans as $kegiatan)
							  	<option value="{{$kegiatan->id}}">{{$kegiatan->nama_kegiatan}}</option>
							  	@endforeach 
							</select>
						</div>
						<div class="form-group">
							<label for="exampleInputEmail1">Tanggal Pengajuan</label>
							<input type="date" class="form-control" id="" name="tanggal" required>
						</div>
						<div class="row" style="margin-left: -15px">
							<div class="col-md-6 col-xs-6 form-group">
								<label class="hidden-xs" for="exampleInputEmail1">Jumlah Barang</label>
								<label class="visible-xs" for="exampleInputEmail1">Jml Barang</label>
								<input type="text" class="form-control" id="jumlah" name="jumlah" oninput="subtotals()">
								<span id="alertjumlah" style="color: red"></span>
							</div>
							<div class="col-md-6 col-xs-6">
								<label for="exampleInputEmail1">Unit Jumlah</label>
								<input type="text" class="form-control" id="" name="unit" placeholder="ex : kg, ml, lusin">
							</div>
						</div>
						<div class="form-group">
							<label for="exampleInputEmail1">Perkiraan biaya</label>
							<input type="text" class="form-control" id="nominal" name="nominal" oninput="subtotals()" required>
							<span id="alertnominal" style="color: red"></span>
						</div>
						<div class="form-group">
							<label for="exampleInputEmail1">Sub Total</label>
							<input type="text" class="form-control" id="subtotal" name="subtotal" readonly>
						</div>
						<div class="form-group">
							<label for="exampleInputEmail1">Keterangan</label>
							<input type="text" class="form-control" id="" name="keterangan" required>
						</div>
						
						<button id="submit" type="submit" class="btn btn-success btn-lg">Simpan</button>
						{{csrf_field()}}
					</form>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-3 hidden-xs hidden-sm">
		<div class="panel panel-default">
			<div class="panel-body" style="text-align: center">
					<div>
						<img src="{{asset('img/contract.svg')}}" style="width: 50px;height: 50px">
					</div>
					<div>
						<h3 style="color: #1c7bd9"><b>Silahkan ajukan dana yang diperlukan untuk kegiatan anda</b></h3>
					</div>
				</div>
		</div>
	</div>
</div>
</div>
<script type="text/javascript">
	function subtotals(){
		if(document.getElementById("jumlah").value!="" && document.getElementById("nominal").value!=""){
			document.getElementById("subtotal").value=document.getElementById("jumlah").value*document.getElementById("nominal").value;
		}
		else if(document.getElementById("jumlah").value==""){
			document.getElementById("subtotal").value=document.getElementById("nominal").value;
		}

		if(document.getElementById("nominal").value!="" && document.getElementById("nominal").value.search(/[a-z ~ ` ! @ # $ % ^ & * ( ) _ - + = | \ / ' ' " " ; : ? > . < ,]/g) !== -1 || document.getElementById("nominal").value!="" && document.getElementById("nominal").value == ""){
			document.getElementById("alertnominal").innerHTML = "Isi dengan angka";
			document.getElementById("subtotal").value="";
		}
		else{
			document.getElementById("alertnominal").innerHTML = "";
		}

		if(document.getElementById("jumlah").value!="" && document.getElementById("jumlah").value.search(/[a-z ~ ` ! @ # $ % ^ & * ( ) _ - + = | \ / ' ' " " ; : ? > . < ,]/g) !== -1 ){
			document.getElementById("alertjumlah").innerHTML = "Isi dengan angka";
			document.getElementById("subtotal").value="";
		}
		else{
			document.getElementById("alertjumlah").innerHTML = "";
		}

		if(document.getElementById("jumlah").value!="" && document.getElementById("jumlah").value == ""){
			document.getElementById("subtotal").value=document.getElementById("nominal").value;
		}

	}

</script>


@endsection
