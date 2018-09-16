@extends('layout.peneliti')
@section('title', 'penggunaan dana')
@section('styles')
<link rel="stylesheet" type="text/css" href="{{URL::asset('css/table.css')}}">
@endsection
@section('content')
<div class="container">
<ol class="breadcrumb" style="font-size: 20px">
	<li><a href="/"></i> Beranda</a></li>
	<li class="active">Daftar Pengajuan Dana Kegiatan</li>
</ol>
	@if($trans_proyeks!=null)
	<div class="row">
		<div class="panel panel-default">
			<div class="container-fluid">
				<div class="panel-body">
					{{-- pc --}}
					<h2 class="hidden-xs" style="color: #448aff;text-align: center;"> Pengajuan Penggunaan Dana </h2>
					<h3 class="hidden-xs" style="color: #808080;text-align: center">{{$kegiatan_tp->kegiatan->nama_kegiatan}}</h3>
					{{-- mobile --}}
					<h3 class="visible-xs" style="color: #448aff;text-align: center;"> Pengajuan Penggunaan Dana </h3>
					<h4 class="visible-xs" style="color: #808080;text-align: center">{{$kegiatan_tp->kegiatan->nama_kegiatan}}</h4>
					<hr>
					<div class="table-responsive">
					<table class="table table-striped">
						<br>
						@if($status=="available")
							<a class="btn-top" style="margin-right: 15px;" href="{{url('/formDana/'.$id_kegiatan)}}" class="btn btn-primary"> <span class="glyphicon glyphicon-plus"></span> Tambah Pengajuan</a>
							<h4 class="pull-right" style="color: #448aff"><b>Dana : Rp {{number_format($kegiatan_tp->kegiatan->saldo)}}</b></h4>
						@else
							<button class="btn btn-primary" disabled> <span class="glyphicon glyphicon-plus"></span> Tambah Pengajuan</button>
						@endif
						<br>
						<br>
						<thead>
							<tr class="row-name">
								<th>Tanggal</th>
								<th>Jumlah Barang</th>
								<th>Unit Jumlah</th>
								<th>Perkiraan Biaya</th>
								<th>Subtotal</th>
								<th>Keterangan</th>
								<th>Status</th>
								<th>Edit/Hapus</th>
							</tr>
						</thead>
						<tbody>
							@foreach($trans_proyeks as $trans_proyek)
							<tr class="row-content">
								<td> <h4>{{$trans_proyek->transaksi->tanggal}} </h4></td>
								<td style="text-align: center"><h4>{{$trans_proyek->jumlah}}</h4></td>
								<td style="text-align: center"><h4>{{$trans_proyek->unit}}</h4></td>
								<td style="text-align: center"><h4>Rp {{number_format($trans_proyek->perkiraan_biaya)}}</h4></td>
								<td><h4>Rp {{number_format($trans_proyek->transaksi->nominal)}}</h4></td>
								<td><h4>{{$trans_proyek->keterangan}}</h4></td>
								@if($trans_proyek->transaksi->status == 3)
									<td><h4><span class="label label-warning">Menunggu</span></h4></td>
									<td>		
									<form class="form-inline" method="POST" action="{{url('/hapusPengajuan/'.$trans_proyek->transaksi->id)}}" enctype="multipart/form-data">
									<div class="form-group">
										<button class="btn btn-info edit" data-toggle="modal" data-target="#editpengajuan" data-id="{{$trans_proyek->transaksi->id}}" type="button">
										<i class="fa fa-pencil-square-o"></i>
									</button>
									<input type="hidden" name="_method" value="DELETE"/>
									<button class="btn btn-danger edit" id="delete" data-id="{{$trans_proyek->transaksi->id}}" data-name="{{$trans_proyek->transaksi->keterangan}}" type="submit">
										<i class="fa fa-trash" aria-hidden="true"></i>
									</button>
									</div>
									{{csrf_field()}}
									</form>
								</td>
								@elseif($trans_proyek->transaksi->status == 1)
									<td><h4><span class="label label-success">Selesai</span></h4></td>
									<td>					
									<div class="form-group">
										<button class="btn btn-info edit" data-toggle="modal" data-target="#editpengajuan" type="button" disabled>
										<i class="fa fa-pencil-square-o"></i>
										</button>
										<input type="hidden" name="_method" value="DELETE"/>
										<button class="btn btn-danger edit" id="delete" type="submit" disabled>
											<i class="fa fa-trash" aria-hidden="true"></i>
										</button>
									</div>

								</td>
								@endif
								
							</tr>
							@endforeach
						</tbody>

					</table>
					{{ $trans_proyeks->links() }}
					</div>
				</div>
			</div>
		</div>
		
	</div>
	@else
	<div class="row" style="text-align: center">
		<div class="hidden-xs">
			<img src="{{asset('img/signature.svg')}}" style="width: 200px; height: 200px; margin-top: 100px">
			<h2>Belum ada pengajuan penggunaan dana</h2>
			@if($status=="available")
				<a class="btn btn-primary" style="margin-right: 15px;" href="{{url('/formDana/'.$id_kegiatan)}}" class="btn btn-primary btn-success pull-right" > <h4><span class="glyphicon glyphicon-plus"></span> Tambah Pengajuan</h4></a>
			@else
				{{-- <button class="btn btn-primary" disabled> <h4><span class="glyphicon glyphicon-plus"></span> Tambah Pengajuan</h4></button> --}}
				<h4><div class="alert alert-danger" role="alert">Waktu kegiatan telah berakhir</div></h4>
			@endif
		</div>
		<div class="visible-xs">
			<img src="{{asset('img/signature.svg')}}" style="width: 150px; height: 150px">
			<h4>Belum ada pengajuan penggunaan dana</h4>
		</div>
	</div>
	@endif
</div>


<!-- Modal Edit Pengajuan-->
<div class="modal fade" id="editpengajuan" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h3 class="modal-title" id="myModalLabel" style="color: #ffffff;text-align: center"><b>Ubah Pengajuan Dana</b>
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class="fas fa-times"></i></span></button>
		        </h3>
			</div>
			<div class="modal-body">
				<form method="POST" action="{{url('/editpengajuan')}}">
					{{csrf_field()}}
					<input type="text" name="id_transaksi" id="id_transaksi" value="" hidden>
               		<span id="count" value="0"></span>
               		<input name="_method" type="hidden" value="PUT">
					<div class="form-group">
						<label for="exampleInputEmail1">Tanggal Pengajuan</label>
						<input type="date" class="form-control" id="tanggal" name="tanggal" required>
					</div>
					<input type="" name="countrow" hidden>
					<div class="row" style="margin-left: -15px">
						<div class="col-md-6 col-xs-6 form-group">
							<label for="exampleInputEmail1">Jumlah</label>
							<input type="text" class="form-control" id="jumlahs" name="jumlah" oninput="validasiform()">
							<span id="alertjumlahs" style="color: red"></span>
						</div>
						<div class="col-md-6 col-xs-6">
							<label for="exampleInputEmail1">Unit Jumlah</label>
							<input type="text" class="form-control" id="unit" name="unit" placeholder="ex : kg, ml, lusin">
						</div>
					</div>
					<div class="form-group">
						<label for="exampleInputEmail1">Perkiraan biaya</label>
						<input type="text" class="form-control" id="nominals" name="nominal" oninput="validasiform()">
						<span id="alertnominals" style="color: red"></span>
					</div>
					<div class="form-group">
						<label for="exampleInputEmail1">Sub Total</label>
						<input type="text" class="form-control" id="subtotals" name="subtotal" readonly>
					</div>
					<div class="form-group">
						<label for="exampleInputEmail1">Keterangan</label>
						<input type="text" class="form-control" id="keterangan" name="keterangan" required>
					</div>
				
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button id="simpan" type="submit2" class="btn btn-primary">Simpan</button>
				</form>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">

function validasiform(){
		if(document.getElementById("jumlahs").value!="" && document.getElementById("nominals").value!=""){
			document.getElementById("subtotals").value=document.getElementById("jumlahs").value*document.getElementById("nominals").value;
		}
		else if(document.getElementById("jumlahs").value==""){
			document.getElementById("subtotals").value=document.getElementById("nominals").value;
		}
		else if(document.getElementById("jumlahs").value!="" && document.getElementById("jumlahs").value == ""){
			document.getElementById("subtotals").value=document.getElementById("nominals").value;
		}

		if(document.getElementById("jumlahs").value!="" && document.getElementById("jumlahs").value.search(/[a-z ~ ` ! @ # $ % ^ & * ( ) _ - + = | \ / ' ' " " ; : ? > . < ,]/g) != -1 ){
			document.getElementById("alertjumlahs").innerHTML = "Isi dengan angka";
			document.getElementById("subtotals").value="";
			document.getElementById("simpan").setAttribute("disabled","disabled");
		}
		else{
			document.getElementById("alertjumlahs").innerHTML = "";
		}


		if(document.getElementById("nominals").value!="" && document.getElementById("nominals").value.search(/[a-z ~ ` ! @ # $ % ^ & * ( ) _ - + = | \ / ' ' " " ; : ? > . < ,]/g) != -1){
			document.getElementById("simpan").setAttribute("disabled","disabled");
			document.getElementById("alertnominals").innerHTML = "Isi dengan angka";
			document.getElementById("subtotals").value="";
			
		}
		else{
			document.getElementById("alertnominals").innerHTML = "";
		}

		if((document.getElementById("jumlahs").value !="" && document.getElementById("jumlahs").value.search(/[a-z ~ ` ! @ # $ % ^ & * ( ) _ - + = | \ / ' ' " " ; : ? > . < ,]/g) == -1 ) && (document.getElementById("nominals").value !="" && document.getElementById("nominals").value.search(/[a-z ~ ` ! @ # $ % ^ & * ( ) _ - + = | \ / ' ' " " ; : ? > . < ,]/g) == -1)){
			document.getElementById("simpan").removeAttribute("disabled","disabled");
		}

	}

</script>


@endsection

@section('script')
<script type="text/javascript">
$('button#delete').on('click',function(e){
    e.preventDefault();
    var form = $(this).parents('form');
    var nama = $(e.currentTarget).attr('data-name');
    var tabel = $(e.currentTarget).attr('data-table');
    swal({
      title: 'Hapus',
      text: "Apakah anda yakin akan menghapus pengajuan "+nama+" ? ",
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Hapus',
      cancelButtonText: 'Batal',
      confirmButtonClass: 'btn btn-success',
      cancelButtonClass: 'btn btn-danger',
      buttonsStyling: false
    },
    function (isConfirm) {
        if(isConfirm) form.submit();
    });
  });
</script>



<script type="text/javascript">
  $('#editpengajuan').on('show.bs.modal', function(event) {
        var link = $(event.relatedTarget);
        var id = link.data('id');
        var modal = $(this);
        modal.find('#id_transaksi').val(id);
        modal.find('#tanggal').val("");
        modal.find('#jumlahs').val("");
        modal.find('#nominals').val("");
        modal.find('#subtotals').val("");
        modal.find('#unit').val("");
        modal.find('#keterangan').val("");
        
        $.ajax({
          url : 'http://'+ window.location.host +'/geteditdana/' + id,
          type: "GET",
          dataType: "json",
          success:function(data) {
          	modal.find('#tanggal').val(data.tanggal);
          	modal.find('#jumlahs').val(data.jumlah);
          	modal.find('#nominals').val(data.perkiraan_biaya);
          	modal.find('#subtotals').val(data.nominal);
          	modal.find('#unit').val(data.unit);
            modal.find('#keterangan').val(data.keterangan);
          },
          error: function (request, status, error) {
		    alert(this.url);
		  }
        });
  });
</script>


@endsection