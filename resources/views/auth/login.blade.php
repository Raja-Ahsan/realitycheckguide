@extends('layouts.website.master')
@section('title', $page_title)
<style>
    .log-forms {
    padding: 30px;
    margin: 20px;
    border-radius: 20px;
    background: linear-gradient(315deg, #ffc430, #ffffff);
    box-shadow: 18px 20px 20px 0px rgb(0 0 0 / 21%), 0 6px 20px rgb(0 0 0 / 8%);
    }
    .login-sec {
        padding: 30px 0;
    }

    .btn-form {
        font-family: 'Familjen Grotesk', sans-serif !important;
        font-size: 14px !important;
        font-weight: 500 !important;
        margin-top: 10px !important;
        background-color: #020202 !important;
        color:rgb(255, 255, 255) !important;
        padding: 10px 20px !important;
        border-radius: 5px !important;
        border: 1px solid #ffc430 !important;
        cursor: pointer;
        transition: all .3s !important;
        display: inline-block !important;
    }
    .login-head{
        font-family: 'Familjen Grotesk', sans-serif !important;
        font-size: 24px !important;
        font-weight: 500 !important;
        margin-top: 10px !important;
        color: #020202 !important;
        margin-bottom: 20px !important;
    }
</style>
@section('content')
    <!-- BANNER SEC -->
    <section class="inner-banner registration-banner"
    style="margin-top: 80px; height: 200px; background-size: cover; 
           background-image: url('{{ !empty($banner->image) 
                ? asset('public/admin/assets/images/banner/' . $banner->image) 
                : asset('public/admin/assets/images/images.png') }}'); 
           width:100%;">
</section>
    <section class="login-sec">
        <div class="container py-5">
            <div class="row">
                <div class="col-md-6 login-content">
                    <div class="login-content-text mt-5">
                        <h4 class="text-uppercase hd-42 heading mb-20"><span>WELCOME</span> TO</h4>
                        <h1 class="login-head hd-20 mb-20 text-capitalize">Reality Check Guide
                        </h1>
                        <p class="hd-20 fw-medium text-capitalize">Reality Check Guide is a video-based platform created to help people explore careers through honest, real-life experiences. Whether you're a student, a career changer, or simply curious, our mission is to show you what jobs are really like — before you commit time, money, or energy into the wrong path.</p>
                    </div>
                </div>
                <div class="col-md-6 form-bg card-body" data-aos="flip-left" data-aos-easing="linear"
                data-aos-duration="1500">
                    <div class="log-forms">
                        <h2 class="login-head text-uppercase hd-42 heading mb-20">LOGIN NOW</h2>
                        @if (Session::has('error'))
                            <p class="alert alert-danger" id="error-alert">{{ Session::get('error') }}</p>
                        @endif
                        @if (Session::has('message'))
                            <p class="alert alert-success" id="success-alert">{{ Session::get('message') }}</p>
                        @endif
                        <form method="POST" action="{{ route('user.authenticate') }}">
                            @csrf
                            <div class="form-group field-wrap">
                                <input class="input-field form-control mb-2" name="email" value="{{ old('email') }}" type="email"
                                    placeholder="Email Address" style="border: 1px solid #cd8904;">
                                <span style="color: red">{{ $errors->first('email') }}</span>
                            </div>
                            <div class="form-group field-wrap">
                                <input class="input-field form-control mb-2" type="password" placeholder="Password" name="password" required
                                    autocomplete="current-password" style="border: 1px solid #cd8904;">
                                <span style="color: red">{{ $errors->first('password') }}</span>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-form mx-auto d-flex justify-content-center text-capitalize w-100 mb-20" name="form1">Login</button>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="flexCheckDefault">
                                <label class=" fs-18" for="flexCheckDefault">
                                    Keep me logged in
                                </label>
                            </div>
                        </form>
                        <div class="form-under-btn">
                            <div class="forgot"><a href="{{ route('forgot-password') }}">Forgot Password?</a></div>
                            <p>Don't have an account? <a href="{{ route('sign-up') }}">Register</a> </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    </main>
    <!-- BANNER SEC -->
@endsection
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var errorAlert = document.getElementById('error-alert');
        if (errorAlert) {
            setTimeout(function() {
                errorAlert.style.display = 'none';
            }, 10000); // 10 seconds
        }
    });
</script>
