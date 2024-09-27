@extends('layouts/blankLayout')

@section('title', 'Login Basic - Pages')

@section('page-style')
@vite([ 'resources/assets/vendor/scss/pages/page-auth.scss' ])
@endsection

@section('content')
<div class="container-xxl">
  <div class="authentication-wrapper authentication-basic container-p-y">
    <div class="authentication-inner">
      <!-- Register -->
      <div class="card px-sm-6 px-0">
        <div class="card-body">
          <!-- Logo -->
          <div class="app-brand justify-content-center">
            <a href="{{ url('/') }}" class="app-brand-link gap-2">
              <span class="app-brand-logo demo">@include('_partials.macros', ["width" => 25, "withbg" => 'var(--bs-primary)'])</span>
            </a>
          </div>
          <!-- /Logo -->
          <h4 class="mb-1">Welcome to your Valomnia Dashboard! 👋</h4>
          <p class="mb-6">Please sign in to your account and start the adventure</p>

          <form id="formAuthentication" action="{{ route('login') }}" method="POST">
            @csrf
              <div class="mb-6">
              <label for="organisation" class="form-label">Organisation</label>
              <input type="text" class="form-control" id="organisation" name="organisation" placeholder="Enter your organisation" autofocus required>
            </div>
            <div class="mb-6">
              <label for="email" class="form-label">Email</label>
              <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
            </div>
            <div class="mb-6 form-password-toggle">
              <label class="form-label" for="password">Password</label>
              <div class="input-group input-group-merge">
                <input type="password" id="password" class="form-control" name="password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="password" required />
                <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
              </div>
            </div>
            <div class="mb-8">
              <div class="d-flex justify-content-between mt-8">
                <div class="form-check mb-0 ms-2">
                  <input class="form-check-input" type="checkbox" id="remember-me">
                  <label class="form-check-label" for="remember-me">
                    Remember Me
                  </label>
                </div>
              </div>
            </div>
            <div class="mb-6">
              <button class="btn btn-primary d-grid w-100" type="submit">Login</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <!-- /Register -->
  </div>
</div>
@endsection
