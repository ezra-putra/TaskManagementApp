@extends('layout.main')
@section('content')
<h3>Edit Task</h3>
<form action="#" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="col-md-12">
        <label for="exampleFormControlInput1" class="form-label">Title</label>
        <input type="text" class="form-control" name="title" placeholder="Input task title here..." value="{{ $task->title }}" >
    </div>
    <div class="col-md-12">
        <label for="exampleFormControlInput1" class="form-label">Description</label>
        <textarea class="form-control" name="description" rows="5" placeholder="{{ $task->description }}"></textarea>
    </div>
    <div class="col-md-12 mb-2">
        <label class="form-label" for="due-date">Due Date</label>
        <div class="input-group input-group-merge">
            <input type="text" id="due-date" name="duedate"
                class="form-control flatpickr-basic" placeholder="MM-DD-YYYY" value="{{ $task->duedate }}"/>
        </div>
    </div>

    <div class="d-flex justify-content-end mt-3">
        <button type="submit" class="btn btn-success">Save</button>
    </div>
</form>
@endsection

