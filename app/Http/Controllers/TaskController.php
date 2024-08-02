<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AssignTask;
use App\Models\AssignTasks;
use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TaskController extends Controller
{
    public function listTask()
    {
        $iduser = auth()->id();
        if(Auth::check()){
            if(auth()->user()->role === "Admin")
            {
                $task = Task::orderBy('duedate', 'asc')->get();
            }
            else{
                $user = User::findOrFail($iduser);
                $task = $user->tasks()->orderBy('duedate', 'asc')->get();
            }
            return view('task.task-list', compact('task'));
        }
        return view('task.task-list');
    }

    public function assignTaskForm($id)
    {
        $task= Task::find($id);
        $users = User::where('role', 'User')
        ->whereDoesntHave('tasks', function ($query) use ($id) {
            $query->where('tasks.id', $id);
        })
        ->get();

        $user = User::where('role', 'User')
        ->whereHas('tasks', function ($query) use ($id) {
            $query->where('tasks.id', $id);
        })
        ->get();

        return view('task.assign', compact('task', 'users', 'user'));
    }

    public function assignTask(Request $request)
    {
        $idtask = $request->input('idTask');
        $assignTask = new AssignTask();
        $assignTask->user_id = $request->input('user-assign');
        $assignTask->task_id = $idtask;
        // dd($assignTask);
        $assignTask->save();

        $task = Task::findOrFail($idtask);
        $task->status = "In Progress";
        $task->save();

        return redirect('/task-list')->with('success', 'Task Assigned Successfully!');
    }

    public function edit($id)
    {
        $task= Task::find($id);
        $users = User::where('role', 'User')
        ->whereDoesntHave('tasks', function ($query) use ($id) {
            $query->where('tasks.id', $id);
        })
        ->get();

        return view('task.edit', compact('task', 'users'));
    }

    public function markAsDone($id)
    {
        $task = Task::find($id);
        $task->status = "Finished";
        $task->save();

        return redirect('/task-list')->with('success', 'Task Finished!');
    }

    public function update(Request $request, $id)
    {
        $task= Task::find($id);
        $task->title = $request->input('title');
        $task->description = $request->input('description');
        $task->duedate = $request->input('duedate');
        $task->status = $request->input('status');
        $task->save();

        return redirect('/list-task')->with('success', 'Task Successfully edited!');
    }

    public function createTask(Request $request)
    {
        $formattedDate = Carbon::createFromFormat('Y-m-d', $request->duedate)->format('Y-m-d');
        $iduser = auth()->id();
        $task = new Task;
        $task->title = $request->input('title');
        $task->description = $request->input('description');
        $task->duedate = $formattedDate;
        $task->user_id = $iduser;
        $task->status = "Request";
        // dd($task);
        $task->save();

        return redirect()->back()->with('success', 'Task has been successfully added!');
    }

    public function destroy($id)
    {
        $task = Task::findOrFail($id);
        $task->delete();

        return redirect()->back()->with('success', 'Task has been deleted!');
    }


}
