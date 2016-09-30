@extends('layouts.master')

@section('style')
    <link rel="stylesheet" href="<?php echo asset('css/navbar.css')?>" type="text/css">
@endsection



@section('navbar')

<nav class="navbar navbar-default navbar-static-top">
        <div class="container">
            <div class="navbar-header">
                <!-- Collapsed Hamburger -->
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                    <span class="sr-only">Toggle Navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <!-- Branding Image -->
                <a class="navbar-brand" href="{{ url('/') }}">
                    MindGigs &trade;
                </a>
            </div>
            <div class="collapse navbar-collapse" id="app-navbar-collapse">
                <!-- Left Side Of Navbar -->
                <div class="nav navbar-nav navbar-left">
                    <form class="navbar-form"  method="post" action="/searchjobs">
                        <div class="input-group">
                            {!! csrf_field() !!}

                            <input type="text" name="tosearch" class="form-control" style="width: 280px" placeholder="Search for services">
                            <div class="input-group-btn"> <input type="submit"  class="btn btn-default" value="Search"> </div>
                        </div>
                    </form>
                </div>

                <div class="nav navbar-nav">
                    <form class="navbar-form" role="search">
                        <a class="btn btn-default" href="{{ url('/faq') }}">FAQ</a>
                        <a class="btn btn-default" href="{{ url('/home') }}">Home</a>
                        <a class="btn btn-default" href="{{ url('/scoreboard') }}">Scoreboard</a>
                        <a class="btn btn-default" href="{{ url('/dashboard') }}">Dashboard</a>
                        <a class="btn btn-default" href="{{ url('/blogs') }}">Blogs</a>
                        <a class="btn btn-default" href="{{ url('/searchjobs') }}">Jobs Search</a>

                    </form>
                </div>

                <!-- Right Side Of Navbar -->
                <ul class="nav navbar-nav navbar-right">
                    <!-- Authentication Links -->
                    @if (Auth::guest())
                        <li><a href="{{ url('/login') }}"> <span class="glyphicon glyphicon-log-in"> Login</span></a></li>
                        <li><a href="{{ url('/register') }}"><span class="glyphicon glyphicon-user"> Register</span></a></li>
                    @else
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                {{ Auth::user()->name }} <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="{{ url('/'.Auth::user()->name.'/') }}"><i class="fa fa-btn fa-user"></i>View My Profile</a></li>
                                <li><a href="{{ url('/'.Auth::user()->name.'/blogs') }}"><i class="fa fa-btn fa-user"></i>View My Blogs</a></li>
                                <li><a href="{{ url('/'.Auth::user()->name.'/dashboard') }}"><i class="fa fa-btn fa-user"></i>View My Dashboard</a></li>
                                <li><a href="{{ url('/logout') }}"><i class="fa fa-btn fa-sign-out"></i>Logout</a></li>
                            </ul>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>

@endsection

<!-- JavaScripts -->

@section('scripts')
    <script type="text/javascript">
        $(function () {
            "use strict";

            $(window).scroll(function () {
                var $scrollTop = $(this).scrollTop();

                if ($scrollTop > 100) {
                    $(".navbar").addClass("navbar-active");
                } else {
                    $(".navbar").removeClass("navbar-active");
                }
            });


        });
    </script>

@endsection
