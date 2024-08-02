<head>
    <meta name="_token" content="{{ csrf_token() }}">
</head>
@extends('layout.main')
@section('content')
    <div class="col-md-12 mb-3">
        <div class="row">
            <div class="d-flex justify-content-between">
                <div class="col-md-6 me-2">
                    <form action="{{ url('search') }}" method="GET">
                        <input type="text" class="form-control" name="search" id="searchInput" placeholder="Search tasks..." value="{{ request()->input('search') }}">
                    </form>
                </div>
                <div class="col-md-6 me-2">
                    <form action="{{ url('search-owner') }}" method="GET">
                        <input type="text" class="form-control" name="searchOwner" id="searchOwner" placeholder="Search owner..." value="{{ request()->input('searchOwner') }}">
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-3">
                <h4>Request</h4>
                <div id="requestTasks" class="item">
                    @foreach ($taskreq as $t)
                    <div class="card">
                        <div class="card-body">
                            <h5 class="mb-2">{{ $t->title }}</h5>
                            <span class="badge bg-warning mb-2">{{ $t->status }}</span>
                            <h6>{{ \Carbon\Carbon::parse($t->duedate)->format('d M Y') }}</h6>
                            <p style="font-size:12px;">By {{ $t->user->name }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="col-md-3">
                <h4>In Progress</h4>
                <div id="inProgressTasks" class="item">
                    @foreach ($taskprog as $t)
                    <div class="card">
                        <div class="card-body">
                            <h5 class="mb-2">{{ $t->title }}</h5>
                            <span class="badge bg-primary mb-2">{{ $t->status }}</span>
                            <h6>{{ \Carbon\Carbon::parse($t->duedate)->format('d M Y') }}</h6>
                            <p style="font-size:12px;">By {{ $t->user->name }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="col-md-3">
                <h4>Finished</h4>
                <div id="finishedTasks" class="item">
                    @foreach ($taskfin as $t)
                    <div class="card">
                        <div class="card-body">
                            <h5 class="mb-2">{{ $t->title }}</h5>
                            <span class="badge bg-success mb-2">{{ $t->status }}</span>
                            <h6>{{ \Carbon\Carbon::parse($t->duedate)->format('d M Y') }}</h6>
                            <p style="font-size:12px;">By {{ $t->user->name }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="col-md-3">
                <h4>Overdue</h4>
                <div id="overdueTasks" class="item">
                    @foreach ($taskover as $t)
                    <div class="card">
                        <div class="card-body">
                            <h5 class="mb-2">{{ $t->title }}</h5>
                            <span class="badge bg-danger mb-2">{{ $t->status }}</span>
                            <h6>{{ \Carbon\Carbon::parse($t->duedate)->format('d M Y') }}</h6>
                            <p style="font-size:12px;">By {{ $t->user->name }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.js"></script>
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <script >
        $(document).ready(function() {
            $('#searchInput').on('keyup', function() {
                let query = $(this).val();

                $.ajax({
                    url: "{{ url('search') }}",
                    method: 'GET',
                    data: { search: query },
                    success: function(data) {
                        $('#requestTasks').html(data.request || '');
                        $('#inProgressTasks').html(data.inProgress || '');
                        $('#finishedTasks').html(data.finish || '');
                        $('#overdueTasks').html(data.overdue || '');
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                    }
                });
            });

            $('#searchInput').trigger('keyup');
        });

        $(document).ready(function(){
            $('#searchOwner').on('keyup', function() {
                let query = $(this).val();
                $.ajax({
                    url: "{{ url('search-owner') }}",
                    method: 'GET',
                    data: { searchOwner: query },
                    success: function(data) {
                        $('#requestTasks').html(data.request || '');
                        $('#inProgressTasks').html(data.inProgress || '');
                        $('#finishedTasks').html(data.finish || '');
                        $('#overdueTasks').html(data.overdue || '');
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                    }
                });
            });
            $('#searchOwner').trigger('keyup');
        });
    </script>

    <script type="text/javascript">
        $.ajaxSetup({ headers: { 'csrftoken' : '{{ csrf_token() }}' } });
    </script>
@endsection
