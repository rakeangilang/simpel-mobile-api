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
	<div class="col-md-12 col-xs-12">
		<div class="panel panel-default">
			<div class="container-fluid">
				<div class="panel-body">
					<div style="text-align: center">
						<h2 class="hidden-xs" style="color: #448aff;text-align: center;"> Form Pengajuan Penggunaan Dana </h2>
						<h3 class="hidden-xs" style="color: #808080;text-align: center">{{$kegiatan->nama_kegiatan}}</h3>
					</div>
					<hr>
					<form method="POST" action="{{url('/mengajukanDana')}}">
						<input type="text" name="id_kegiatan" id="id_kegiatan" value="{{$kegiatan->id}}" hidden>
							<div class="row">
								<div class="col-md-4">
									<div class="form-group">
										<label for="exampleInputEmail1">Tanggal Pengajuan</label>
										<input type="date" class="form-control" id="" name="tanggal" required>
									</div>
								</div>
							</div>
							<div class="table table-responsive">
							<table id="data_table" class="table table-striped">
								<thead>
									<tr>
										<th class="col-md-4">Keterangan</th>
										<th class="col-md-2">Jumlah</th>
										<th class="col-md-2">Unit jumlah</th>
										<th class="col-md-2">Perkiraan biaya</th>
										<th class="col-md-2">Subtotal</th>
										<th></th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>
											<input type="text" class="form-control" id="keterangan" name="keterangan[]" required>
										</td>
										<td>
											<input type="text" class="form-control" id="jumlah" name="jumlah[]" oninput="subtotals(0)">
											<span id="alertjumlah" style="color: red"></span>
										</td>
										<td>
											<input type="text" class="form-control" id="unit" name="unit[]" placeholder="ex : kg, ml, lusin, dll">
										</td>
										<td>
											<input type="text" class="form-control" id="nominal" name="nominal[]" oninput="subtotals(0)" required>
											<span id="alertnominal" style="color: red"></span>
										</td>
										<td>
											<input type="text" class="form-control" id="subtotal" name="subtotal[]" readonly>
										</td>
										<td>
											<button class="btn btn-danger" onclick="myDeleteFunction(this)"><i class="fas fa-trash-alt"></i> Delete</button>
										</td>
									</tr>
								</tbody>

								<tfoot>
									<tr>
										<td colspan="4">
											<button class="btn btn-primary" onclick=" myCreateFunction()"><i class="fas fa-plus"></i> Tambah</button>
										</td>
									</tr>
								</tfoot>
						
							</table>
							</div>
						<button id="simpan" type="submit" class="btn btn-success btn-lg pull-right">Simpan</button>
						{{csrf_field()}}
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
</div>
<script type="text/javascript">
	var t = $('#data_table').DataTable({
      'paging'      : false,
      'lengthChange': false,
      'searching'   : false,
      'ordering'    : false,
      'info'        : true,
      'autoWidth'   : true
    })

	
  function myCreateFunction() {
      var table = document.getElementById("data_table");
      var row = table.insertRow(1);
      var cell1 = row.insertCell(0);
      var cell2 = row.insertCell(1);
      var cell3 = row.insertCell(2);
      var cell4 = row.insertCell(3);
      var cell5 = row.insertCell(4);
      var cell6 = row.insertCell(5);

  		let count = table.rows.length - 2

      cell1.innerHTML = '<input type="text" class="form-control" id="keterangan'+count+'" name="keterangan[]" required><br>';
      cell2.innerHTML = '<input type="text" class="form-control" id="jumlah'+count+'" name="jumlah[]" oninput="subtotals('+count+')"><span id="alertjumlah'+count+'" style="color: red"></span><br>';
      cell3.innerHTML =  '<input type="text" class="form-control" id="unit'+count+'" name="unit[]" placeholder="ex : kg, ml, lusin"><br>'; 
      cell4.innerHTML = '<input type="text" class="form-control" id="nominal'+count+'" name="nominal[]" oninput="subtotals('+count+')" required><span id="alertnominal'+count+'" style="color: red"></span><br>'; 
      cell5.innerHTML =  '<input type="text" class="form-control" id="subtotal'+count+'" name="subtotal[]" readonly><br>';
      cell6.innerHTML = '<button class="btn btn-danger" onclick="myDeleteFunction(this)"><i class="fas fa-trash-alt"></i> Delete</button><br><br><br>';

  }

  function myDeleteFunction(self) {
    // console.log($(self).closest("tr").index())
      document.getElementById("data_table").deleteRow($(self).closest("tr").index()+1);
  }

	function subtotals(count){
		var jumlah = 'jumlah'
		var alertjumlah = 'alertjumlah'
		var nominal = 'nominal'
		var alertnominal = 'alertnominal'
		var subtotal = 'subtotal'
		if(count != 0){
			jumlah = jumlah + count
			alertjumlah = alertjumlah + count
			nominal = nominal + count
			alertnominal = alertnominal + count
			subtotal = subtotal + count
		}
		if(document.getElementById(jumlah).value!="" && document.getElementById(nominal).value!=""){
			document.getElementById(subtotal).value=document.getElementById(jumlah).value*document.getElementById(nominal).value;
		}
		else if(document.getElementById(jumlah).value==""){
			document.getElementById(subtotal).value=document.getElementById(nominal).value;
		}

		else if(document.getElementById(jumlah).value!="" && document.getElementById(jumlah).value == ""){
			document.getElementById(subtotal).value=document.getElementById(nominal).value;
		}

		if(document.getElementById(nominal).value!="" && document.getElementById(nominal).value.search(/[a-z ~ ` ! @ # $ % ^ & * ( ) _ - + = | \ / ' ' " " ; : ? > . < ,]/g) !== -1 || document.getElementById(nominal).value!="" && document.getElementById(nominal).value == ""){
			document.getElementById(alertnominal).innerHTML = "Isi dengan angka";
			document.getElementById(subtotal).value="";
			// document.getElementById("simpan").setAttribute("disabled","disabled");
		}
		else{
			document.getElementById(alertnominal).innerHTML = "";
		}

		if(document.getElementById(jumlah).value!="" && document.getElementById(jumlah).value.search(/[a-z ~ ` ! @ # $ % ^ & * ( ) _ - + = | \ / ' ' " " ; : ? > . < ,]/g) !== -1 ){
			document.getElementById(alertjumlah).innerHTML = "Isi dengan angka";
			document.getElementById(subtotal).value="";
			// document.getElementById("simpan").setAttribute("disabled","disabled");
		}
		else{
			document.getElementById(alertjumlah).innerHTML = "";
		}
		// console.log(count);
		// for(i=0; i<=count; i++){
		// 	if((document.getElementById(jumlah).value !="" && document.getElementById(jumlah).value.search(/[a-z ~ ` ! @ # $ % ^ & * ( ) _ - + = | \ / ' ' " " ; : ? > . < ,]/g) == -1 ) && (document.getElementById(nominal).value !="" && document.getElementById(nominal).value.search(/[a-z ~ ` ! @ # $ % ^ & * ( ) _ - + = | \ / ' ' " " ; : ? > . < ,]/g) == -1)){
		// 	document.getElementById("simpan").removeAttribute("disabled","disabled");
		// 	}
		// }
		

	}

</script>


@endsection
