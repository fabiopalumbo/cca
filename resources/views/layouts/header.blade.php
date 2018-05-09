<header id="header" class="navbar" style="position: fixed; width: 99%;">
    @if(Auth::check())
    <ul class="nav navbar-nav navbar-avatar pull-right">
        <li class="dropdown ">
            <a class="dropdown-toggle">
                <span class="hidden-xs-only" style="font-size:14px;color:black !important;">
                {{Auth::user()->name}}
                    </span>
                <span class="thumb-small avatar inline"><img src=<?php if ($src = Auth::user()->image_url){
                                                                            echo $src;
                                                                        }else{
                                                                            echo "src/images/perfil.png";
                                                                        }?>  class="img-circle" style="width: 40px;height: 40px;"></span>
                <b class="caret hidden-xs-only"></b>
            </a>
            <ul class="dropdown-menu pull-right dropdown-perfil">

                <li><a href="#">{{ trans('view.header.settings') }}</a></li>
                <li><a href="{{URL::to('modify')}}">{{ trans('view.header.profile') }}</a></li>
                <li><a href="#">{{ trans('view.header.notifications') }}</a></li>
                <li><?php $repo = new \App\Repositories\GroupsAndPermissionRepository;
                    if($repo->adminCheck()){
                                ?><a href="{{URL::to('admin')}}">Admin</a><?php
                          }?></li>
                <li class="divider"></li>
                <li> <a href="{{ URL::to('logout') }}" >{{ Lang::get('view.header.logout') }}</a></li>
            </ul>
        </li>
    </ul>
    @endif
    <div   id="logo" class="navbar-brand pull-left" style="margin-left: -31px;"><a href="{{URL::to('/')}}"><img id="img" src="{{Config::get('client.logo')}}" style="margin-left: 30px" ></a></div>
    <div style="margin-top: -37px">
        @if(!Auth::check())
            <a  id="toggle" type="button" class="pull-right" ><span></span></a>
            <div id="menu"  style="margin-left: 87%;">
                <ul>
                    @if(Route::getCurrentRoute()->getPath()!='register')<li><a href="{{URL::to('register')}}">{{ Lang::get('view.header.register') }} &nbsp;<i class="fa fa-user"></i></a></li>@endif
                    @if(Route::getCurrentRoute()->getPath()!='login')<li><a href="{{URL::to('login')}}">{{ Lang::get('view.header.login') }} &nbsp;<i class="fa fa-user"></i></a></li>@endif
                </ul>
            </div>
        @endif
    </div>
</header>