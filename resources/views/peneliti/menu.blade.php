    <!-- Left side column. contains the sidebar -->
    <aside class="main-sidebar" style="font-size: 18px">
      {{-- {!! include_action('App\Http\Controller\MenuController', 'sidebar') !!} --}}
      
      <!-- sidebar: style can be found in sidebar.less -->
      <section class="sidebar">
      <div class="user-panel">
        <div class="pull-left image">
          <img src="{{asset('img/man.png')}}" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p style="white-space: normal;">{{auth::user()->pegawai->nama}}</p>
          <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div><br>
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu">
          <li class="header">MENU</li>
          @if(\Request::is('/'))
          <li class="treeview active">
            <a href="/">
              <i class="fas fa-tachometer-alt"></i> <span>Beranda</span>
            </a>
          </li>
          @else
          <li class="treeview">
            <a href="/">
              <i class="fas fa-tachometer-alt"></i> <span>Beranda</span>
            </a>
          </li>
          @endif
          @if(\Request::is('kegiatan/*')||\Request::is('berkas/*'))
          <li class="treeview active">
            <a href="#">
              <i class="fa fa-edit"></i> <span>Kegiatan</span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
               @foreach($tipekegiatans as $tipekegiatan)
               @if(\Request::is('kegiatan/'.$tipekegiatan->id)||\Request::is('berkas/'.$tipekegiatan->id))
               <li class="active"><a href="/kegiatan/{{$tipekegiatan->id}}"><i class="fas fa-angle-double-right"></i> {{$tipekegiatan->nama_tipe_kegiatan }}</a></li>
               @else
               <li><a href="/kegiatan/{{$tipekegiatan->id}}"><i class="fas fa-angle-double-right"></i> {{$tipekegiatan->nama_tipe_kegiatan }}</a></li>
               @endif
               @endforeach
            </ul>
          </li>
          @else
          <li class="treeview ">
            <a href="#">
              <i class="fa fa-edit"></i> <span>Kegiatan</span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
               @foreach($tipekegiatans as $tipekegiatan)
               <li><a href="/kegiatan/{{$tipekegiatan->id}}"><i class="fas fa-angle-double-right"></i> {{$tipekegiatan->nama_tipe_kegiatan }}</a></li>
               @endforeach
            </ul>
          </li>
          @endif
          @if(\Request::is('publikasijurnal'))
          <li class="treeview active">
            <a href="/publikasijurnal">
              <i class="fab fa-leanpub"></i> <span> Publikasi Jurnal</span>
            </a>
          </li>
          @else
          <li class="treeview">
            <a href="/publikasijurnal">
              <i class="fab fa-leanpub"></i> <span> Publikasi Jurnal</span>
            </a>
          </li>
          @endif
          @if(\Request::is('publikasibuku'))
          <li class="treeview active">
            <a href="/publikasibuku">
             <i class="fas fa-book"></i> <span> Publikasi Buku</span>
            </a>
          </li>
          @else
          <li class="treeview">
            <a href="/publikasibuku">
             <i class="fas fa-book"></i> <span> Publikasi Buku</span>
            </a>
          </li>
          @endif
        </ul>
      </section>
      <!-- /.sidebar -->
    </aside>