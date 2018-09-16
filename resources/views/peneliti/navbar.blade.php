
<!-- Site wrapper -->
  <div class="wrapper">
    <header class="main-header" >
      <!-- Logo -->
       <!-- Logo -->
    <a href="#" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>SIMP</b></span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>SIMP</b>eneliti</span>
    </a>
      <!-- Header Navbar: style can be found in header.less -->
      <nav class="navbar navbar-static-top" >
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button" >
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </a>

      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <!-- Messages: style can be found in dropdown.less-->
          <li class="dropdown messages-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              @if($kegiatanss!=null)
                <i class="far fa-envelope"></i>
                <span class="label label-danger">{{count($kegiatanss)}}</span>
              @else
                <i class="far fa-envelope"></i>
              @endif
            </a>
            <ul class="dropdown-menu">
              @if($kegiatanss!=null)
              <li class="header">Anda memiliki {{count($kegiatanss)}} pesan kolaborasi</li>
              @else
              <li class="header">Anda memiliki 0 pesan kolaborasi</li>
              @endif
              <li>
                <!-- inner menu: contains the actual data -->
                <ul class="menu">
                  <li><!-- start message -->
                    @if($kegiatanss!=null)
                      @foreach($kegiatanss as $index => $kegiatan)
                        <a href="#" data-toggle="modal" data-target="#kolaborasi" data-id="{{$kegiatan->id}}">
                          <div class="pull-left">
                            <img src="{{asset('img/document.svg')}}" class="img-circle" alt="User Image">
                          </div>
                          <h4>
                            {{$kegiatan->tipe_kegiatan->nama_tipe_kegiatan}}                       
                          </h4>
                          <p>{{$kegiatan->nama_kegiatan}}</p>
                        </a>
                      @endforeach
                    @else
                    <div class="row" style="text-align: center">
                      <br><br><br>
                      <img src="{{asset('img/mail.svg')}}" style="width: 50px;height: 50px">
                      <h4>Tidak ada pesan</h4>
                    </div>
                    @endif

                  </li>
                  <!-- end message -->
                </ul>
              </li>
            </ul>
          </li>


          <!-- User Account: style can be found in dropdown.less -->
          <li class="dropdown user user-menu" >
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <img src="{{asset('img/man.svg')}}" class="user-image" alt="User Image">
              <span class="hidden-xs">{{auth::user()->pegawai->nama}}</span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
                <img src="{{asset('img/man.svg')}}" class="img-circle" alt="User Image">

                <p>
                  {{auth::user()->pegawai->nama}} - {{auth::user()->pegawai->peneliti_psb->departemen->nama_departemen}}
                  {{-- <small>Member since Nov. 2012</small> --}}
                </p>
              </li>
              <!-- Menu Body -->
{{--               <li class="user-body">
                <div class="row">
                  <div class="col-xs-4 text-center">
                    <a href="#">Followers</a>
                  </div>
                  <div class="col-xs-4 text-center">
                    <a href="#">Sales</a>
                  </div>
                  <div class="col-xs-4 text-center">
                    <a href="#">Friends</a>
                  </div>
                </div>
                <!-- /.row -->
              </li> --}}
              <br>
              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="pull-left">
                  <a href="/profil" class="btn btn-default btn-flat">Profile</a>
                </div>
                <div class="pull-right">
                  <a class="btn btn-default" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                  <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                          {{ csrf_field() }}
                  </form>
                </div>
              </li>
            </ul>
          </li>
        </ul>
      </div>
      </nav>
    </header>
  </div>


     <!-- Modal -->
<div class="modal fade" id="kolaborasi" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title" id="myModalLabel" style="color: #ffffff;text-align: center"><b>Informasi Kolaborasi</b>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class="fas fa-times"></i></span></button>
            </h3>
      </div>
      <div class="modal-body">
       <form class="form-horizontal">  
          <div class="form-group">
            <label for="inputEmail3" class="col-md-2 col-sm-2 control-label">Tipe Kegiatan</label>
            <div class="col-md-10" style="margin-top: 15px">
              <p class="tipe_kegiatan"></p>
            </div>
          </div>
          <div class="form-group">
            <label for="inputEmail3" class="col-md-2 col-sm-2 control-label">Nama Kegiatan</label>
            <div class="col-md-10 col-sm-10" style="margin-top: 7px">
              <p class="nama_kegiatan"></p>
            </div>
          </div>
          <div class="form-group">
            <label for="inputEmail3" class="col-md-2 col-sm-2 control-label">Judul Penelitian</label>
            <div class="col-md-10 col-sm-10" style="margin-top: 7px">
              <p class="judul"></p>
            </div>
          </div>
          <div class="form-group">
            <label for="inputEmail3" class="col-md-2 col-sm-2 control-label">Tanggal Kegiatan</label>
            <div class="col-md-10 col-sm-10" style="margin-top: 15px">
              <p class="tanggal"></p>
            </div>
          </div>
        </form>
      </div>
      
      <div class="modal-footer">
        <h4>Apakah anda setuju dengan kolaborasi ini?</h4>
        <div class="row pull-right">
          <div class="col-md-5 col-sm-5 col-xs-5" style="margin-right: -10px">
            <form role= "form" id="setuju" method="POST" action="{{url('/setujukolaborasi')}}" enctype="multipart/form-data">
               <input type="text" name="kegiatan_id" id="kegiatan_id" value="" hidden>
               <input name="_method" type="hidden" value="PUT">
               <button type="submit" class="btn btn-primary">Setuju</button>
                {{ csrf_field() }}
            </form>
            </div>
            <div class="ol-md-1 col-sm-1 col-xs-1">
            <form role= "form" id="tolak" method="POST" action="{{url('/menolakkolaborasi')}}" enctype="multipart/form-data">
               <input type="text" name="id_kegiatan" id="id_kegiatan" value="" hidden>
               <input name="_method" type="hidden" value="PUT">
               <button type="submit" class="btn btn-danger">Tidak Setuju</button>
                {{ csrf_field() }}
            </form>
          </div>
        </div>
      </div>
     
    </div>
  </div>
</div>


