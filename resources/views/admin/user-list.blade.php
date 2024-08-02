@extends('layout.main')
@section('content')
    <div class="col-md-12">
        <table id="user" class="table table-striped display nowrap" style="width:100%;">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">Role</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $counter = 1
                @endphp
                @foreach ($user as $u)
                <tr>
                    <td>{{ $counter++ }}</td>
                    <td>{{ $u->name }}</td>
                    <td>{{ $u->email }}</td>
                    <td>{{ $u->role }}</td>
                    <td>
                        <a class="btn btn-flat-info" data-bs-toggle="modal" href="#modalRole" onclick="getModalEditRole({{ $u->id }})">
                            <i class="fa fa-pencil"></i>
                            <span>Role</span>
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
    </div>

    <div class="modal fade" id="modalRole" tabindex="-1" role="basic" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-transparent">
                    <h5 class="text-center mb-1" id="addNewCardTitle">User Role Information</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-sm-5 mx-50 pb-5">
                    <div id="inputForm">

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.js"></script>
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>
    <script>
            $('#user').DataTable();
    </script>
    <script>
        function getModalEditRole(id) {
            $.ajax({
                type:'POST',
                url:'{{route("edit.role-form")}}',
                data:'_token= <?php echo csrf_token() ?> &id='+id,
                success:function(data) {
                    $("#inputForm").html(data.msg);
                }
            });
        }
    </script>
@endsection
