{{-- <x-guest-layout> --}}
    <head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login Form</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body class="bg-light">
    <!-- Session Status -->
    @if (session('status'))
        <div class="alert alert-success mb-4">
            {{ session('status') }}
        </div>
    @endif

    <!-- Error Message -->
    @if ($errors->any())
        <div class="alert alert-danger mb-4">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- <form method="POST" action="{{ route('login') }}">
        @csrf

        <label>Username</label>
        <input type="text" name="username" required autofocus>

        <label>Password</label>
        <input type="password" name="password" required>

        <button type="submit">Login</button>
    </form> --}}
     <div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="card shadow-lg p-4" style="width: 100%; max-width: 400px;">
      <h3 class="text-center mb-4">Login</h3>
      <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="mb-3">
          <label for="username" class="form-label">username</label>
          <input type="text" class="form-control" name="username" required />
        </div>
        <div class="mb-3">
          <label>Password</label>
        <input type="password" class="form-control" name="password" required>
        </div>
        <div class="d-grid">
          <button type="submit" class="btn btn-primary">Login</button>
        </div>
        <div class="text-center mt-3">
          <small><a href="#">Lupa password?</a></small>
        </div>
      </form>
    </div>
  </div>

{{-- </x-guest-layout> --}}