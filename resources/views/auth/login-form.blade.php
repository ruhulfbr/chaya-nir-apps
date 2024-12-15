<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">
</head>

<body>
<div id="auth">

    <div class="container">
        <div class="row">
            <div class="col-md-6 col-sm-8 mx-auto">
                @if ($login_error = Session::get('login-error'))
                    <div class="alert alert-danger color-info shadow">
                        {{ $login_error }}
                    </div>
                @endif
                <div class="card pt-4">
                    <div class="card-body">
                        <div class="text-center mb-5">
                            <img src="{{ asset('img/finnaf-logo.png') }}" class='img-fluid mb-2'
                                 style="max-width:200px" alt="">
                            <h3>Login</h3>
                        </div>
                        <form action="{{ route('login') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="email">Email <span class="text-danger">*</span></label>
                                        <input type="email" id="email" placeholder="Enter email"
                                               class="form-control @if($errors->has('email')) border-danger @endif"
                                               name="email" value="{{ @old('email') }}" required />
                                        @if ($errors->has('email'))
                                            <span class="text-danger my-1">{{ $errors->first('email') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="password">Password <span class="text-danger">*</span></label>
                                        <input type="password" id="email" placeholder="Enter password"
                                               class="form-control @if($errors->has('password')) border-danger @endif"
                                               name="password" value="" required/>
                                        @if ($errors->has('password'))
                                            <span class="text-danger my-1">{{ $errors->first('password') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </diV>

                            <div class="clearfix mt-2 d-flex justify-content-end">
                                <button class="btn btn-primary" type="submit">Sign In</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
</body>

</html>
