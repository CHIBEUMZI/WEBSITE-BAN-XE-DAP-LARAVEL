<!DOCTYPE html>
<html lang="en">

<head>
  <base href="{{ env('APP_URL')}}">
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Quản lý cửa hàng xe đạp</title>
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Quản lý cửa hàng xe đạp</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
  
   
    <link href="bootstrap/css/mdb.min.css" rel="stylesheet">
    <link href="bootstrap/css//mdb.min.css.map" rel="stylesheet">
    <link href="bootstrap/css/mdb.rtl.min.css" rel="stylesheet">
    
   
    <script src="bootstrap/js/mdb.es.min.js"></script>
    <script src="bootstrap/js/mdb.es.min.js.map"></script>
    <script src="bootstrap/js/mdb.umd.min.js"></script>
    <script src="bootstrap/js/mdb.umd.min.js.map"></script>
    

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <title>Login</title>

</head>

<body>
  <section class="vh-100" style="background-color: #9A616D;">
    <div class="container py-5 h-100">
      <div class="row d-flex justify-content-center align-items-center h-100">
        <div class="col col-xl-10">
          <div class="card" style="border-radius: 1rem;">
            <div class="row g-0">
              <div class="col-md-6 col-lg-5 d-none d-md-block" style="margin-top:100px;">
                <img src="img/bikeLogin.webp"
                  alt="login form" class="img-fluid" style="border-radius: 1rem 0 0 1rem;" />
              </div>
              <div class="col-md-6 col-lg-7 d-flex align-items-center">
                <div class="card-body p-4 p-lg-5 text-black">

                  <form action="{{ route('auth.login') }}" method="POST">
                    @csrf
                    <div class="d-flex align-items-center mb-3 pb-1">
                      <i class="fas fa-cubes fa-2x me-3" style="color: #ff6219;"></i>
                      <span class="h1 fw-bold mb-0">ĐAM MÊ BẤT TẬN </span>
                    </div>

                    <h5 class="fw-normal mb-3 pb-3" style="letter-spacing: 1px;">Sign into your account</h5>
                    <div class="form-group">
                      <label for="exampleInputEmail1">Email address</label>
                      <input type="text" class="form-control" id="email" aria-describedby="emailHelp" placeholder="Enter email" name="email" value="{{old('email')}}">
                      @if ($errors -> has('email'))
                      <span class="error-message">{{
                         $errors->first('email') }}
                      </span>
                    @endif
                    </div>

                    <div class="form-group">
                      <label for="exampleInputPassword1">Password</label>
                      <input type="password" class="form-control" id="password_hash" placeholder="Password" name="password">
                      @if ($errors -> has('password'))
                      <span class="error-message">{{
                         $errors->first('password') }}
                      </span>
                    @endif
                    </div>

                    <div class="form-group">
                      <button data-mdb-button-init data-mdb-ripple-init class="btn btn-dark btn-lg btn-block" type="submit">Login</button>
                    </div>
                    <div class="group">
                      <a class="small text-muted" href="{{route('password.form')}}">Forgot password?</a>
                      <p class="mb-5 pb-lg-2" style="color: #393f81;">Don't have an account? <a href="{{route('register.form')}}"
                          style="color: #393f81;">Register here</a></p>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <style>
    .form-group {
      padding: 10px;
    }
    .error-message{
      color: red;
      font-size: 12px;
      font-family: Arial, Helvetica, sans-serif
    }
  </style>
</body>

</html>