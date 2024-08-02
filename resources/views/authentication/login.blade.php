<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Login - Task Management</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    </head>
    <body>
        <div class="container py-1 h-100 vh-100">
            <div class="row d-flex align-items-center justify-content-center h-100">
                <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-center mt-3">
                                <img src="{{ asset('img/black.png') }}" alt="Logo" class="img-fluid" width="250">
                            </div>
                            <h4 class="text-center mt-3 mb-1">Login</h4>
                            <p class="text-center mt-3 mb-2" style="font-size: 16px">Welcome to Task Management Apps!</p>
                            <form action="{{ route('login') }}" class="mt-2" method="POST">
                                @csrf
                                <div class="mb-1">
                                    <label for="mail" class="form-label">Email</label>
                                    <input type="text" class="form-control" id="email" name="email"
                                        placeholder="ezra@example.com" aria-describedby="email" tabindex="1"
                                        autofocus />
                                </div>

                                <div class="mb-1">
                                    <div class="d-flex justify-content-between">
                                        <label class="form-label" for="password">Password</label>
                                    </div>
                                    <div class="input-group input-group-merge form-password-toggle">
                                        <input type="password" class="form-control form-control-merge"
                                            id="password" name="password" tabindex="2"
                                            placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                            aria-describedby="password" />
                                    </div>
                                </div>
                                <button class="btn btn-dark w-100 mb-1 mt-4" tabindex="4">SIGN IN</button>
                            </form>
                            <p class="text-center mt-2">
                                Doesn't have an account?
                                <a href="/register">
                                    <span>Create an account</span>
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if ($errors->any())
        <div class="modal fade" id="failModal" tabindex="-1" aria-labelledby="failModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Login Failed</h5>
                        </div>
                        <div class="modal-body">
                            @foreach ($errors->all() as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Try Again</button>
                        </div>
                    </div>
                </div>
            </div>
            <script>
                $(document).ready(function() {
                    $('#failModal').modal('show');
                    setTimeout(function() {
                        $('#failModal').modal('hide');
                    }, 5000);
                });
            </script>
        @endif

        @if (session('success'))
            <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Success</h5>
                        </div>
                        <div class="modal-body">
                            {{ session('success') }}
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
            <script>
                $(document).ready(function() {
                    $('#successModal').modal('show');
                    setTimeout(function() {
                        $('#successModal').modal('hide');
                    }, 5000);
                });
            </script>
        @endif
    </body>
</html>
