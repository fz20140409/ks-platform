<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        @if(Auth::check())
        <div class="user-panel">
            <div class="pull-left image">
                <img src="@if(empty(Auth::user()->avatar)) /img/default_avatar_male.jpg @else {{Auth::user()->avatar}}   @endif" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <p>{{Auth::user()->name}}</p>
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>
        @endif
        <ul class="sidebar-menu">
           {!!  Cache::tags(['user_menu'])->get('user_menu_'.Auth::id()) !!}
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>