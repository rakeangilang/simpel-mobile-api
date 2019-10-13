@extends('layout.peneliti')
@section('title', 'Simpel')

@section('content')
<div class="container">
		<div class="row">
			<div class="col-md-3 col-xs-12 pull-right">
				<div class="form-group">
					<div class="input-group">
						<div class="input-group-addon"><i class="fas fa-filter"></i></div>
						<form method="GET" role="filter" action="{{url('/filterKegiatan')}}">
							<select class="form-control" name="tipekegiatan" onchange="this.form.submit()" >
								@if($filter=="all")
								<option selected="true" value="all">Tanpa Filter</option>
								@else
								<option value="all">Tanpa Filter</option>
								@endif
								@foreach($tipekegiatans as $tipekegiatan)
									@if($filter==$tipekegiatan->id)
									<option selected="true" value="{{$tipekegiatan->id}}">{{$tipekegiatan->nama_tipe_kegiatan}}</option>
									@else
									<option value="{{$tipekegiatan->id}}">{{$tipekegiatan->nama_tipe_kegiatan}}</option>
									@endif
								@endforeach
							</select>
						</form>
					</div>
				</div>
			</div>
			<div class="col-md-5 col-xs-12 pull-right">
				<form method="GET" role="search" action="{{url('/searchKegiatan')}}">
					<div class="input-group">
						<input type="text" class="form-control" placeholder="Cari kegiatan anda" name="search">
						<div class="input-group-btn">
							<button class="btn btn-default" type="submit"><i class="glyphicon glyphicon-search"></i></button>
						</div>
					</div>
				</form>
			</div>
		</div>
		<br>
		@if($kegiatans!=null)
			@foreach($kegiatans as $kegiatan)
				<div class="panel panel-default" style="border-bottom: 4px solid #2196F3;">
					<div class="panel-body">
						<div class="row">
							<div class="col-md-1 col-xs-4">
								<img src="{{asset('img/document.svg')}}" style="width: 60px;height: 60px">
							</div>
							
							<div class="col-md-5 col-xs-8" style="margin-left: -20px">
								<h3><b>{{$kegiatan->tipe_kegiatan->nama_tipe_kegiatan}}</b></h3>
							</div>
						</div>
						<br>
						<div class="panel panel-default">
							<div class="panel-body">
								
								<h2 class="hidden-xs"><b>{{$kegiatan->nama_kegiatan}}</b></h2>
								<h4 class="visible-xs"><b>{{$kegiatan->nama_kegiatan}}</b></h4>

								<i class="far fa-calendar-alt"></i><span> Mulai : {{Carbon\Carbon::parse($kegiatan->tanggal_awal)->format('d F Y')}}</span><br>
								<i class="far fa-calendar-alt"></i><span> Berakhir : {{Carbon\Carbon::parse($kegiatan->tanggal_akhir)->format('d F Y')}}</span><br>
								<a href="{{url('/detailKegiatan/'.$kegiatan->id)}}" class="btn btn-primary pull-right"><i class="fas fa-info-circle"></i> Detail</a>
								<div class="btn-group pull-right">
						          <button class="btn btn-warning " style="margin-right: 10px"  type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
						            <i class="fa fa-edit"></i> <span> Edit </span><span class="caret"></span>
						          </button>
						          <ul class="dropdown-menu" role="menu">
						            <li><a href="/vieweditkegiatan/{{$kegiatan->id}}">Edit Kegiatan</a></li>
						            <li class="divider"></li>
						            <li><a href="/berkas/{{$kegiatan->id}}">Tambah Berkas</a></li>
						            <li class="divider"></li>
						            <li><a href="/getDana/{{$kegiatan->id}}">Penggunaan Dana</a></li>
						          </ul>
						        </div>
							</div>
						</div>
						
					</div>
				</div>
			@endforeach
			{{ $kegiatans->links() }}
		@else
		<br><br>
		<div class="row" style="text-align: center">
			<div class="hidden-xs">
				<img src="{{asset('img/filewarning.svg')}}" style="width: 200px; height: 200px">
				<h2>Belum ada kegiatan yang diikuti</h2>
			</div>
			<div class="visible-xs">
				<img src="{{asset('img/filewarning.svg')}}" style="width: 150px; height: 150px">
				<h4>Belum ada kegiatan yang diikuti</h4>
			</div>
		</div>
		@endif

</div>

<!-- Modal -->
<div class="modal fade" id="detail" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header"> 
        <h3 class="modal-title" id="myModalLabel" style="color: #ffffff;text-align: center"><b>Detail Kegiatan</b>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="text-align: center"><span aria-hidden="true"><i class="fas fa-times"></i></span></button>
        </h3>
      </div>

      <div class="modal-body">
       <form class="form-horizontal">
       	  <input type="text" name="kegiatans_id" id="kegiatan_id" value="" hidden>  
          <div class="form-group">
            <label for="inputEmail3" class="col-md-2 col-sm-2 control-label">Tipe Kegiatan</label>
            <div class="col-md-10" style="margin-top: 15px">
              <p class="tipe_kegiatans"></p>
            </div>
          </div>
          <div class="form-group">
            <label for="inputEmail3" class="col-md-2 col-sm-2 control-label">Nama Kegiatan</label>
            <div class="col-md-10 col-sm-10" style="margin-top: 7px">
              <p class="nama_kegiatans"></p>
            </div>
          </div>
          <div class="form-group">
            <label for="inputEmail3" class="col-md-2 col-sm-2 control-label">Judul Penelitian</label>
            <div class="col-md-10 col-sm-10" style="margin-top: 7px">
              <p class="juduls"></p>
            </div>
          </div>
          <div class="form-group">
            <label for="inputEmail3" class="col-md-2 col-sm-2 control-label">Tanggal Kegiatan</label>
            <div class="col-md-10 col-sm-10" style="margin-top: 15px">
              <p class="tanggals"></p>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
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