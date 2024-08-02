@extends('layout.main')
@section('content')
<h3>Assign Task</h3>
<form action="{{ route('assign.post') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="col-md-12">
        <label for="exampleFormControlInput1" class="form-label">Title</label>
        <input type="text" class="form-control" name="title" placeholder="Input task title here..." value="{{ $task->title }}" readonly>
    </div>
    <div class="col-md-12">
        <label for="exampleFormControlInput1" class="form-label">Description</label>
        <textarea class="form-control" name="description" rows="5" placeholder="{{ $task->description }}" readonly></textarea>
    </div>
    <div class="col-md-12">
        <label class="form-label" for="due-date">Due Date</label>
        <div class="input-group input-group-merge">
            <input type="text" id="due-date" name="duedate"
                class="form-control flatpickr-basic" placeholder="MM-DD-YYYY" value="{{ $task->duedate }}" readonly/>
        </div>
    </div>
    <div class="col-md-12 mb-1">
        <label class="form-label" for="select-user">User</label>
        <select class="select2 form-select" id="select-user" name="user-assign">
            <option value="">--Choose User--</option>
            @foreach ($users as $u)
            <option value="{{ $u->id }}">{{ $u->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="d-flex justify-content-end mt-3">
        <input type="hidden" name="idTask" value="{{ $task->id }}">
        <button type="submit" class="btn btn-success">Save</button>
    </div>
</form>

<div class="row">
    Assigned Member:
    <div class="col-md-2">
        @if (!empty($user))
            @foreach ($user as $u)
                <span class="badge bg-primary">{{ $u->name }}</span>
            @endforeach
        @else
            <p>-</p>
        @endif
    </div>
</div>
@endsection

