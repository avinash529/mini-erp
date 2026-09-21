<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Login - Mini ERP</title>

   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
   @vite(['resources/css/app.css'])
</head>

<body class="mini-erp-shell">

   <div class="container">
      <div class="row justify-content-center align-items-center min-vh-100">
         <div class="col-md-5 col-lg-4">

            <div class="card login-card mini-erp-card">

               <div class="card-header text-center">
                  <h4 class="mb-0">Mini ERP</h4>
               </div>

               <div class="card-body">

                  @if($errors->any())
                  <div class="alert alert-danger mb-3">
                     {{ $errors->first() }}
                  </div>
                  @endif

                  <form method="POST" action="{{ route('login.submit') }}">
                     @csrf

                     <div class="mb-3">
                        <label class="form-label">Email</label>

                        <input type="email" name="email" class="form-control" value="{{ old('email') }}" required
                           autofocus>
                     </div>

                     <div class="mb-3">
                        <label class="form-label">Password</label>

                        <input type="password" name="password" class="form-control" required>
                     </div>

                     <button class="btn btn-primary w-100 mt-2">
                        Login
                     </button>
                  </form>

               </div>

            </div>

         </div>
      </div>
   </div>

</body>

</html>