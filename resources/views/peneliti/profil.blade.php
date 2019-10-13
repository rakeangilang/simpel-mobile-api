@extends('layout.peneliti')
@section('title', 'Profil')
@section('breadcrumb', 'Profil')
@section('styles')
<link rel="stylesheet" type="text/css" href="{{URL::asset('bower_components/select2/dist/css/select2.css')}}">
<link rel="stylesheet" type="text/css" href="{{URL::asset('css/tab.css')}}">
@endsection
@section('content')
<div class="container">

		<div class="panel panel-default">
			<div class="panel-body">	
				<!-- Nav tabs -->
				<div class="row">
					<div class="col-md-2 hidden-xs" style="text-align: center">
						<img src="{{asset('img/man.svg')}}" style="border-radius: 50px;">
					</div>
					<div class="visible-xs" style="text-align: center">
						<img src="{{asset('img/man.svg')}}" style="border-radius: 50px;height: 150px;width: 150px">
					</div>
					<div class="col-md-5">
						<h3 style="color:#1c7bd9"><b>{{$pegawai->nama}}</b></h3>
						<h4>{{$pegawai->nomor_hp}}</h4>
						<h4 style="white-space: normal">{{$pegawai->email}}</h4>
						<h4>Departemen {{$peneliti_psb->departemen->nama_departemen}}</h4>
						<h4>{{$peneliti_psb->departemen->fakultas->nama_fakultas}}</h4>
						<a href="{{url('/getPDF')}}" class="btn btn-success" target="_blank"><i class="fas fa-download"></i> Unduh CV</a>
						<button type="button" class="btn btn-warning" data-toggle="modal" data-target="#editprofil">
						  <i class="fa fa-edit"></i> Edit Profil
						</button>

					</div>
				</div>
				<br><br>
				    <div class="card">
				        <ul class="nav nav-tabs" role="tablist">
				          <li role="presentation" class="active"><a href="#riset" aria-controls="home" role="tab" data-toggle="tab"><i class="fa fa-home"></i>  <span>Rekam Jejak</span></a></li>
				          <li role="presentation"><a href="#koneksi" aria-controls="profile" role="tab" data-toggle="tab"><i class="fa fa-user"></i>  <span>Kolaborasi Saya</span></a></li>
				          
				        </ul>
				        
				        <!-- Tab panes -->
				        <div class="tab-content">
				          <div role="tabpanel" class="tab-pane active" id="riset">
				          		<div class="row">
								{{-- penelitian --}}
								<div class="col-md-6">
									<div class="box box-info" style="border: 1px solid #00c0ef; border-top: 5px solid #00c0ef;">
										<div class="box-header with-border">
											<h4>Penelitian</h4>
											<div class="box-tools pull-right">
												<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
												</button>
											</div>
										</div>
										<div class="box-body">
											@if(count($penelitians))
											    @foreach($penelitians as $penelitian)
													<div class="panel panel-default" style="margin-top: -10px">
											 			<div class="panel-body">
															<p>{{$penelitian->judul}}</p>
															<a href="{{url('/detailKegiatan/'.$penelitian->id)}}" class="btn btn-primary"><i class="fas fa-info-circle"></i> Detail</a>
													 	</div>
													</div>
												@endforeach
											@else
												<div class="row" style="text-align: center">
													<div class="hidden-xs">
														<img src="{{asset('img/filewarning.svg')}}" style="width: 70px; height: 70px">
														<h4>Belum ada rekam jejak</h4>
													</div>
													<div class="visible-xs">
														<img src="{{asset('img/filewarning.svg')}}" style="width: 50px; height: 50px">
														<h4>Belum ada rekam jejak</h4>
													</div>
												</div>
											@endif
										</div>
										<div class="box-footer clearfix">
											{{-- <a href="javascript:void(0)" class="btn btn-sm btn-info btn-flat pull-left"><i class="fas fa-plus-circle"></i> Tambah </a>
											<a href="javascript:void(0)" class="btn btn-sm btn-default btn-flat pull-right">View All Orders</a> --}}
										</div>
									</div>
								</div>
								{{-- jurnal --}}
								<div class="col-md-6">
									<div class="box box-info" style="border: 1px solid #00c0ef; border-top: 5px solid #00c0ef;">
										<div class="box-header with-border">
											<h4>Pengalaman publikasi di berkala ilmiah</h4>
											<div class="box-tools pull-right">
												<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
												</button>
											</div>
										</div>
										<div class="box-body">
										@if(count($publikasijurnals))
											@foreach($publikasijurnals as $publikasijurnal)
												<div class="panel panel-default" style="margin-top: -10px">
												 	<div class="panel-body">												
														<p>{{$publikasijurnal->judul_artikel}}</p>
														<form class="form-inline" role="form" method="POST" action="{{url('/hapusPubjurnal/'.$publikasijurnal->id)}}" enctype="multipart/form-data">
															<div class="form-group">
																<a href="/getPubjurnal/{{$publikasijurnal->id}}" class="btn btn-warning"><i class="fa fa-edit"></i> Edit</a>
																<input type="hidden" name="_method" value="DELETE"/>
																<button type="submit" class="btn btn-danger" id="delete" data-id="{{$publikasijurnal->id}}" data-name="{{$publikasijurnal->judul_artikel}}" data-table="publikasi_jurnal"><i class="fas fa-trash-alt"></i> Hapus</button>
																<a href="{{url('/detailPubjurnal/'.$publikasijurnal->id)}}" class="btn btn-primary"><i class="fas fa-info-circle"></i> Detail</a>
															</div>
															{{csrf_field()}}
														</form>
													</div>
												</div>
											@endforeach
										@else
											<div class="row" style="text-align: center">
												<div class="hidden-xs">
													<img src="{{asset('img/filewarning.svg')}}" style="width: 70px; height: 70px">
													<h4>Belum ada rekam jejak</h4>
												</div>
												<div class="visible-xs">
													<img src="{{asset('img/filewarning.svg')}}" style="width: 50px; height: 50px">
													<h4>Belum ada rekam jejak</h4>
												</div>
											</div>
										@endif
										</div>
										<div class="box-footer clearfix">
											<a href="publikasijurnal" class="btn btn-info pull-right"><i class="fas fa-plus-circle"></i> Tambah </a>
										
										</div>
									</div>

								</div>
							</div>
							<div class="row">
								{{-- seminar ilmiah --}}
								<div class="col-md-6">
									<div class="box box-info" style="border: 1px solid #00c0ef; border-top: 5px solid #00c0ef;">
										<div class="box-header with-border">
											<h4>Seminar Ilmiah</h4>
											<div class="box-tools pull-right">
												<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
												</button>
											</div>
										</div>
										<div class="box-body">
										@if(count($seminars))
											@foreach($seminars as $seminar)
												<div class="panel panel-default" style="margin-top: -10px">
												 	<div class="panel-body">
															<p>{{$seminar->judul}}</p>
															<a href="{{url('/detailKegiatan/'.$seminar->id)}}" class="btn btn-primary"><i class="fas fa-info-circle"></i> Detail</a>
													</div>
												</div>
											@endforeach
										@else
											<div class="row" style="text-align: center">
												<div class="hidden-xs">
													<img src="{{asset('img/filewarning.svg')}}" style="width: 70px; height: 70px">
													<h4>Belum ada rekam jejak</h4>
												</div>
												<div class="visible-xs">
													<img src="{{asset('img/filewarning.svg')}}" style="width: 50px; height: 50px">
													<h4>Belum ada rekam jejak</h4>
												</div>
											</div>
										@endif
										</div>
										<div class="box-footer clearfix">

										</div>
									</div>
								</div>
								{{-- publikasi buku --}}
								<div class="col-md-6">
									<div class="box box-info" style="border: 1px solid #00c0ef; border-top: 5px solid #00c0ef;">
										<div class="box-header with-border">
											<h4>Publikasi Buku</h4>
											<div class="box-tools pull-right">
												<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
												</button>
											</div>
										</div>
										<div class="box-body">
										@if(count($publikasibukuu))
											@foreach($publikasibukuu as $publikasibuku)
												<div class="panel panel-default" style="margin-top: -10px">
												 	<div class="panel-body">
														<p>{{$publikasibuku->judul_buku}} </p>
														<form class="form-inline" role="form" method="POST" action="{{url('/hapusPubbuku/'.$publikasibuku->id)}}" enctype="multipart/form-data">
															<div class="form-group">
																<a href="/getPubbuku/{{$publikasibuku->id}}" class="btn btn-warning"><i class="fa fa-edit"></i> Edit</a>
																<input type="hidden" name="_method" value="DELETE"/>
																<button id="delete" data-id="{{$publikasibuku->id}}" data-name="{{$publikasibuku->judul_buku}}" data-table="publikasi_buku" type="submit" class="btn btn-danger"><i class="fas fa-trash-alt"></i> Hapus</button>
															</div>
															{{csrf_field()}}
														</form>
													</div>
												</div>
											@endforeach
										@else
											<div class="row" style="text-align: center">
												<div class="hidden-xs">
													<img src="{{asset('img/filewarning.svg')}}" style="width: 70px; height: 70px">
													<h4>Belum ada rekam jejak</h4>
												</div>
												<div class="visible-xs">
													<img src="{{asset('img/filewarning.svg')}}" style="width: 50px; height: 50px">
													<h4>Belum ada rekam jejak</h4>
												</div>
											</div>
										@endif
										</div>
										<div class="box-footer clearfix">
											<a href="/publikasibuku" class="btn btn-info pull-right"><i class="fas fa-plus-circle"></i> Tambah </a>
			
										</div>
									</div>
								</div>
							</div>
				          </div>
				          <div role="tabpanel" class="tab-pane" id="koneksi" style="text-align: center;overflow-x: scroll;">
				          	@if($koneksi!=null)
				          	<div class="row" style="text-align: center">
								<div class="hidden-xs">
									<h2>Kolaborasi Anda</h2>
								</div>
								<div class="visible-xs">
									<h4>Kolaborasi Anda</h4>
								</div>
							</div>

				          	<script src="{{URL::asset('js/d3.js')}}"></script>
				          	<script type="text/javascript">
								var margin = {top: 20, right: 150, bottom: 20, left: 150},
								 width = 660 - margin.right - margin.left,
								 height = 600 - margin.top - margin.bottom;
 
								var i = 0;

								var tree = d3.layout.tree()
								 .size([height, width]);

								var diagonal = d3.svg.diagonal()
								 .projection(function(d) { return [d.y, d.x]; });

								var svg = d3.select("#koneksi").append("svg")
								 .attr("width", width + margin.right + margin.left)
								 .attr("height", height + margin.top + margin.bottom)
								 .append("g")
								 .attr("transform", "translate(" + margin.left + "," + margin.top + ")");

								root = {!! $koneksi !!}
								update(root)

								function update(source) {

								  // Compute the new tree layout.
								  var nodes = tree.nodes(root).reverse(),
								   links = tree.links(nodes);

								  // Normalize for fixed-depth.
								  nodes.forEach(function(d) { d.y = d.depth * 180; });

								  // Declare the nodesâ€¦
								  var node = svg.selectAll("g.node")
								   .data(nodes, function(d) { return d.id || (d.id = ++i); });

								  // Enter the nodes.
								  var nodeEnter = node.enter().append("g")
								   .attr("class", "node")
								   .attr("transform", function(d) { 
								    return "translate(" + d.y + "," + d.x + ")"; });

								  nodeEnter.append("circle")
								   .attr("r", 10)
								   .style("fill", "#fff");

								  nodeEnter.append("text")
								   .attr("x", function(d) { 
								    return d.children || d._children ? -13 : 13; })
								   .attr("dy", ".35em")
								   .attr("text-anchor", function(d) { 
								    return d.children || d._children ? "end" : "start"; })
								   .text(function(d) { return d.name; })
								   .style("fill-opacity", 1);

								  // Declare the linksâ€¦
								  var link = svg.selectAll("path.link")
								   .data(links, function(d) { return d.target.id; });

								  // Enter the links.
								  link.enter().insert("path", "g")
								   .attr("class", "link")
								   .attr("d", diagonal);

								}
				          	</script>
				          	@else
				          		<div class="row" style="text-align: center">
									<div class="hidden-xs">
										<img src="{{asset('img/koneksi.png')}}" style="width: 200px; height: 200px">
										<h2>Belum ada kolaborasi</h2>
									</div>
									<div class="visible-xs">
										<img src="{{asset('img/koneksi.png')}}" style="width: 150px; height: 150px">
										<h4>Belum ada kolaborasi</h4>
									</div>
								</div>
				          	@endif
				          </div>
				          
				        </div>
			    	</div>

	
			</div>
		</div>

</div>

<!-- edit profil -->
<div class="modal fade" id="editprofil" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title" id="myModalLabel" style="color: #ffffff;text-align: center"><b>Edit Profil</b>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class="fas fa-times"></i></span></button>
        </h3>
      </div>
      <div class="modal-body">
      	<div class="card">
        <ul class="nav nav-tabs" role="tablist">
          <li role="presentation" class="active"><a href="#email" aria-controls="email" role="tab" data-toggle="tab"><i class="fas fa-user"></i>  <span>Username</span></a></li>
          <li role="presentation"><a href="#password" aria-controls="password" role="tab" data-toggle="tab"><i class="fas fa-unlock-alt"></i>  <span>Password</span></a></li>
          
        </ul>
        
        <!-- Tab panes -->
        <div class="tab-content">
          {{-- tab email --}}
          <div role="tabpanel" class="tab-pane active" id="email">
          	<h4 class="modal-title" id="myModalLabel" style="color: #1c7bd9;text-align: center"><b>Edit Username</b></h4>
          	<form method="POST" enctype="multipart/form-data" action="{{ url('/editusername') }}">
	            {{csrf_field()}}
		        <div class="form-group">
		        	<input type="hidden" name="_method" value="PUT">
		            <label for="exampleInputName2">Username</label>
		            <input id="uname"  type="text" name="username" class="form-control" id="exampleInputName2" value="{{$user->username}}" onkeyup="kirimusername()" required>
		            <span class="text-danger" id="alert" style="color: red"></span>
		        </div>
		        <button id="simpan" type="submit" class="btn btn-primary" disabled>Simpan</button>
		    </form>
          </div>
          {{-- tab password --}}
          <div role="tabpanel" class="tab-pane" id="password">
          	<h4 class="modal-title" id="myModalLabel" style="color: #1c7bd9;text-align: center"><b>Edit Password</b></h4>
          	<br>
          	<form class="form-horizontal" method="POST" enctype="multipart/form-data" action="{{ url('/editpassword')}}">
	            {{csrf_field()}}
	            <div class="form-group">
	                <input type="hidden" name="_method" value="PUT">
	                <label for="inputEmail3" class="col-sm-3 control-label">Password Lama</label>
	                <div class="col-sm-9">
	                    <input name="password" type="password" class="form-control" id="passlama" placeholder="Password Lama" onkeyup ="kirimpass()" required>
	                    <b id="wrongpass" style="color: red"></b>
	                </div>
	            </div>
	            

	            <div class="form-group">
	                <label for="inputPassword3" class="col-sm-3 control-label">Password Baru</label>
	                <div class="col-sm-9">
	                    <input name="passbaru" type="password" class="form-control" id="passbaru" placeholder="Minimal 6 karakter" onchange="validasipassword()" onkeyup="validasipassword2()" required>
	                    <b id="needmore" style="color: red"></b>
	                </div>
	            </div>
	            <div class="form-group">
	                <label for="inputPassword3" class="col-sm-3 control-label">Konfirmasi Password Baru</label>
	                <div class="col-sm-9">
	                    <input type="password" class="form-control" id="confirmpass" placeholder="konfirmasi password baru " onkeyup ="passmatch()" required>
	                    <b id="notmatch" style="color: red"></b>
	             
	                </div> 
	            </div>

	            <br>
	            <button id="simpan2" type="submit" class="btn btn-primary" disabled>Simpan</button>
        	</form>
        </div>
        </div>
 

    </div>
  </div>
</div>

@endsection


@section('script')
<script type="text/javascript" src="{{URL::asset('bower_components/select2/dist/js/select2.full.js')}}"></script>
<script type="text/javascript">
	@if(Session::has('msg'))
      swal("{{ Session::get('title')}}","{{ Session::get('msg')}}","{{ Session::get('alert-type')}}");
  	@endif
</script>

<script type="text/javascript">
	function validasipassword(){
        var pass = document.getElementById("passbaru");
        if(pass.value != "" && pass.value.length<6){
            document.getElementById("needmore").innerHTML = "Minimal password 6 karakter";
            document.getElementById("simpan2").setAttribute("disabled","disabled");
        }
        else{
            document.getElementById("needmore").innerHTML = "";
            document.getElementById("simpan2").removeAttribute("disabled","disabled");

        }
    }
    function validasipassword2(){
        var pass = document.getElementById("passbaru");
        if(pass.value.length>=6 || pass.value==""){
            document.getElementById("needmore").innerHTML = "";
            document.getElementById("simpan2").removeAttribute("disabled","disabled");
        }
    }
    function passmatch(){
        var pass1 = document.getElementById("passbaru");
        var pass2 = document.getElementById("confirmpass");
        if(pass1.value != pass2.value && pass2.value != ""){
            document.getElementById("notmatch").innerHTML = "Password tidak sama";
            document.getElementById("simpan2").setAttribute("disabled","disabled");

        }
        else{
            document.getElementById("notmatch").innerHTML = "";
  			document.getElementById("simpan2").removeAttribute("disabled","disabled");
  		}

    }


</script>
<script type="text/javascript">
    function kirimpass(){
        var password = document.getElementById("passlama").value;

        var CSRF_TOKEN = '{{csrf_token()}}';
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN':CSRF_TOKEN
            }
        });
        $.ajax({
            url:'/comparepass',
            type:'POST',
            data:{password:password,_token:CSRF_TOKEN},
            success : function(data){
            	console.log("data: ");
            	console.log(data);
                if(data=="salah" && password!="" ){
                    document.getElementById("wrongpass").innerHTML = "Password tidak sama";
                    document.getElementById("simpan2").setAttribute("disabled","disabled");
                }
                else{
                	 document.getElementById("wrongpass").innerHTML = "";
                	 document.getElementById("simpan2").removeAttribute("disabled","disabled");
                }

            }
        });
    }


     function kirimusername(){
        var username = $('#uname').val();
        var CSRF_TOKEN = '{{csrf_token()}}';

        
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN':CSRF_TOKEN
            }
        });
        $.ajax({
            url:'/compareusername',
            type:'POST',
            data:{username:username,_token:CSRF_TOKEN},
            success : function(data){
                console.log("data: ");
                console.log(data);
                if(data=="ada" && username!=""){
                    document.getElementById("alert").innerHTML = "Username sudah ada";
                    document.getElementById("simpan").setAttribute("disabled","disabled");
                }
                else{
                    document.getElementById("alert").innerHTML = "";
                    document.getElementById("simpan").removeAttribute("disabled","disabled");
                 
                }

                if(username!="" && username.search(/[ ]/g) !== -1){
                    document.getElementById("alert").innerHTML = "Jangan menggunakan spasi";
                    document.getElementById("simpan").setAttribute("disabled","disabled");
                }
            }
        });



    }
</script>

<script type="text/javascript">
$('button#delete').on('click',function(e){
    e.preventDefault();
    var form = $(this).parents('form');
    var nama = $(e.currentTarget).attr('data-name');
    var tabel = $(e.currentTarget).attr('data-table');
    swal({
      title: 'Hapus',
      text: "Anda yakin akan menghapus "+nama+" ? ",
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
@endsection