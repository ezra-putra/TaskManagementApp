@extends('layout.main')
@section('content')
<div class="d-flex justify-content-between">
    <h3>Task List</h3>
    @if (auth()->user()->role == "Admin")
    <a href="#modalAddTask" data-bs-toggle="modal" class="btn btn-outline-dark">
        <i class="fa fa-plus"></i>
    </a>
    @endif
    </div>
    <table id="task" class="table table-striped display nowrap" style="width:100%;">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Title</th>
                <th scope="col">Description</th>
                <th scope="col">Due Date</th>
                <th scope="col">Status</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            @php
                $counter = 1
            @endphp
            @if(auth()->user())
                @foreach($task as $t)
                <tr>
                    <td>{{ $counter++ }}</td>
                    <td>{{ $t->title }}</td>
                    <td>{{ $t->description }}</td>
                    <td>{{ $t->duedate }}</td>
                    <td>
                        @if($t->status == "Request")
                        <span class="badge bg-warning">{{ $t->status }}</span>
                        @elseif ($t->status == "In Progress")
                        <span class="badge bg-primary">{{ $t->status }}</span>
                        @elseif($t->status == "Finish")
                        <span class="badge bg-success">{{ $t->status }}</span>
                        @elseif($t->status == "Overdue")
                        <span class="badge bg-danger">{{ $t->status }}</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('done.post', $t->id) }}" class="btn btn-icon">
                            <i class="fa fa-check me-50"></i>
                        </a>
                        @if (auth()->user()->role == "Admin")
                        <a href="/edit-task/{{ $t->id }}" class="btn btn-icon">
                            <i class="fa fa-pencil me-50"></i>
                        </a>
                        <a href="/assign-task/{{ $t->id }}" class="btn btn-icon" >
                            <i class="fa fa-users me-50"></i>
                        </a>
                        <form method="POST" action="{{ route('task.destroy', $t->id) }}"
                            style="display: inline-block">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-icon btn-icon-danger"
                                onclick="return confirm('Do you want to delete this Task ({{ $t->title }})?');">
                                <i class="fa fa-trash-o"></i>
                            </button>
                        </form>
                        @endif
                    </td>
                </tr>
                @endforeach
            @endif
        </tbody>
    </table>

    <div class="modal fade" id="modalAddTask" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Add New Task</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('task.post') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="col-md-12">
                        <label for="exampleFormControlInput1" class="form-label">Title</label>
                        <input type="text" class="form-control" name="title" placeholder="Input task title here..." required>
                    </div>
                    <div class="col-md-12">
                        <label for="exampleFormControlInput1" class="form-label">Description</label>
                        <textarea class="form-control" name="description" rows="5" placeholder="Input task description here..." required></textarea>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label" for="due-date">Due Date</label>
                        <div class="input-group input-group-merge">
                            <input type="text" id="due-date" name="duedate"
                                class="form-control flatpickr-basic" placeholder="MM-DD-YYYY" required/>
                        </div>
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-between">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Save</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.js"></script>
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <script>
        $('#task').DataTable();

        const tomorrow = new Date();
        tomorrow.setDate(tomorrow.getDate() + 1);

        const tomorrowFormatted = tomorrow.toISOString().split('T')[0];
        flatpickr("#due-date", {
            minDate: tomorrowFormatted,
            dateFormat: "Y-m-d"
        });


    </script>
@endsection

