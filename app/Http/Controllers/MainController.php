<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MainController extends Controller
{
    public function index()
    {
        $iduser = auth()->id();
        if(Auth::check()){
            if(auth()->user()->role === "Admin")
            {
                $taskreq = Task::where('status', 'Request')->orderBy('duedate', 'asc')->get();
                $taskprog = Task::where('status', 'In Progress')->orderBy('duedate', 'asc')->get();
                $taskfin = Task::where('status', 'Finish')->orderBy('duedate', 'asc')->get();
                $taskover = Task::where('status', 'Overdue')->orderBy('duedate', 'asc')->get();
            }
            else{
                $user = User::findOrFail($iduser);
                $tasks = $user->tasks()->orderBy('duedate', 'asc')->get();

                $taskreq = $tasks->where('status', 'Request');
                $taskprog = $tasks->where('status', 'In Progress');
                $taskfin = $tasks->where('status', 'Finish');
                $taskover = $tasks->where('status', 'Overdue');

            }
            return view('index', compact('taskreq', 'taskprog', 'taskfin', 'taskover'));
        }
        return view('index');
    }

    public function search(Request $request)
    {
        if ($request->ajax()) {
            $iduser = auth()->id();
            $searchTerm = $request->input('search', '');

            $output = [
                'request' => '',
                'inProgress' => '',
                'finish' => '',
                'overdue' => ''
            ];

            if (Auth::check()) {
                if (auth()->user()->role === "Admin") {
                    $taskreq = Task::where('status', 'Request')->with('user')
                        ->when($searchTerm, function ($query, $searchTerm) {
                            return $query->where('title', 'LIKE', "%{$searchTerm}%");
                        })
                        ->orderBy('duedate', 'asc')
                        ->get();

                    $taskprog = Task::where('status', 'In Progress')->with('user')
                        ->when($searchTerm, function ($query, $searchTerm) {
                            return $query->where('title', 'LIKE', "%{$searchTerm}%");
                        })
                        ->orderBy('duedate', 'asc')
                        ->get();

                    $taskfin = Task::where('status', 'Finish')->with('user')
                        ->when($searchTerm, function ($query, $searchTerm) {
                            return $query->where('title', 'LIKE', "%{$searchTerm}%");
                        })
                        ->orderBy('duedate', 'asc')
                        ->get();

                    $taskover = Task::where('status', 'Overdue')->with('user')
                        ->when($searchTerm, function ($query, $searchTerm) {
                            return $query->where('title', 'LIKE', "%{$searchTerm}%");
                        })
                        ->orderBy('duedate', 'asc')
                        ->get();
                } else {
                    $user = User::findOrFail($iduser);
                    $tasks = $user->tasks()
                        ->when($searchTerm, function ($query, $searchTerm) {
                            return $query->where('title', 'LIKE', "%{$searchTerm}%");
                        })
                        ->orderBy('duedate', 'asc')
                        ->get();


                    $taskreq = $tasks->where('status', 'Request');
                    $taskprog = $tasks->where('status', 'In Progress');
                    $taskfin = $tasks->where('status', 'Finish');
                    $taskover = $tasks->where('status', 'Overdue');
                }

                // Format output as HTML for each category
                $output['request'] = $taskreq->map(function ($t) {
                    $name = $t->user->name;
                    return "
                    <div class='card'>
                        <div class='card-body'>
                            <h5 class='mb-2'>{$t->title}</h5>
                            <span class='badge bg-warning mb-2'>{$t->status}</span>
                            <h6>" . \Carbon\Carbon::parse($t->duedate)->format('d M Y') . "</h6>
                            <p style='font-size:12px;'>By $name</p>
                        </div>
                    </div>";
                })->implode('');

                $output['inProgress'] = $taskprog->map(function ($t) {
                    $name = $t->user->name;
                    return "
                    <div class='card'>
                        <div class='card-body'>
                            <h5 class='mb-2'>{$t->title}</h5>
                            <span class='badge bg-primary mb-2'>{$t->status}</span>
                            <h6>" . \Carbon\Carbon::parse($t->duedate)->format('d M Y') . "</h6>
                            <p style='font-size:12px;'>By $name </p>
                        </div>
                    </div>";
                })->implode('');

                $output['finish'] = $taskfin->map(function ($t) {
                    $name = $t->user->name;
                    return "
                    <div class='card'>
                        <div class='card-body'>
                            <h5 class='mb-2'>{$t->title}</h5>
                            <span class='badge bg-success mb-2'>{$t->status}</span>
                            <h6>" . \Carbon\Carbon::parse($t->duedate)->format('d M Y') . "</h6>
                            <p style='font-size:12px;'>By $name </p>
                        </div>
                    </div>";
                })->implode('');

                $output['overdue'] = $taskover->map(function ($t) {
                    $name = $t->user->name;
                    return "
                    <div class='card'>
                        <div class='card-body'>
                            <h5 class='mb-2'>{$t->title}</h5>
                            <span class='badge bg-danger mb-2'>{$t->status}</span>
                            <h6>" . \Carbon\Carbon::parse($t->duedate)->format('d M Y') . "</h6>
                            <p style='font-size:12px;'>By $name </p>
                        </div>
                    </div>";
                })->implode('');

                return response()->json($output);
            }

            return response()->json($output);
        }
    }

    public function searchByOwner(Request $request)
    {
        if ($request->ajax()) {
            $iduser = auth()->id();
            $searchTerm = $request->input('searchOwner', '');

            $output = [
                'request' => '',
                'inProgress' => '',
                'finish' => '',
                'overdue' => ''
            ];

            if (Auth::check()) {
                if (auth()->user()->role === "Admin") {
                    // Jika Admin, cari tugas berdasarkan nama pemilik tugas
                    $taskreq = Task::where('status', 'Request')->with('user')
                        ->whereHas('user', function($query) use ($searchTerm) {
                            $query->where('name', 'LIKE', "%{$searchTerm}%");
                        })
                        ->orderBy('duedate', 'asc')
                        ->get();

                    $taskprog = Task::where('status', 'In Progress')->with('user')
                        ->whereHas('user', function($query) use ($searchTerm) {
                            $query->where('name', 'LIKE', "%{$searchTerm}%");
                        })
                        ->orderBy('duedate', 'asc')
                        ->get();

                    $taskfin = Task::where('status', 'Finish')->with('user')
                        ->whereHas('user', function($query) use ($searchTerm) {
                            $query->where('name', 'LIKE', "%{$searchTerm}%");
                        })
                        ->orderBy('duedate', 'asc')
                        ->get();

                    $taskover = Task::where('status', 'Overdue')->with('user')
                        ->whereHas('user', function($query) use ($searchTerm) {
                            $query->where('name', 'LIKE', "%{$searchTerm}%");
                        })
                        ->orderBy('duedate', 'asc')
                        ->get();
                } else {
                    $user = User::findOrFail($iduser);
                    $tasks = $user->tasks()
                        ->whereHas('user', function($query) use ($searchTerm) {
                            $query->where('name', 'LIKE', "%{$searchTerm}%");
                        })
                        ->orderBy('duedate', 'asc')
                        ->get();

                    $taskreq = $tasks->where('status', 'Request');
                    $taskprog = $tasks->where('status', 'In Progress');
                    $taskfin = $tasks->where('status', 'Finish');
                    $taskover = $tasks->where('status', 'Overdue');
                }

                $output['request'] = $taskreq->map(function ($t) {
                    $name = $t->user->name;
                    return "
                    <div class='card'>
                        <div class='card-body'>
                            <h5 class='mb-2'>{$t->title}</h5>
                            <span class='badge bg-warning mb-2'>{$t->status}</span>
                            <h6>" . \Carbon\Carbon::parse($t->duedate)->format('d M Y') . "</h6>
                            <p style='font-size:12px;'>By $name</p>
                        </div>
                    </div>";
                })->implode('');

                $output['inProgress'] = $taskprog->map(function ($t) {
                    $name = $t->user->name;
                    return "
                    <div class='card'>
                        <div class='card-body'>
                            <h5 class='mb-2'>{$t->title}</h5>
                            <span class='badge bg-primary mb-2'>{$t->status}</span>
                            <h6>" . \Carbon\Carbon::parse($t->duedate)->format('d M Y') . "</h6>
                            <p style='font-size:12px;'>By $name </p>
                        </div>
                    </div>";
                })->implode('');

                $output['finish'] = $taskfin->map(function ($t) {
                    $name = $t->user->name;
                    return "
                    <div class='card'>
                        <div class='card-body'>
                            <h5 class='mb-2'>{$t->title}</h5>
                            <span class='badge bg-success mb-2'>{$t->status}</span>
                            <h6>" . \Carbon\Carbon::parse($t->duedate)->format('d M Y') . "</h6>
                            <p style='font-size:12px;'>By $name </p>
                        </div>
                    </div>";
                })->implode('');

                $output['overdue'] = $taskover->map(function ($t) {
                    $name = $t->user->name;
                    return "
                    <div class='card'>
                        <div class='card-body'>
                            <h5 class='mb-2'>{$t->title}</h5>
                            <span class='badge bg-danger mb-2'>{$t->status}</span>
                            <h6>" . \Carbon\Carbon::parse($t->duedate)->format('d M Y') . "</h6>
                            <p style='font-size:12px;'>By $name </p>
                        </div>
                    </div>";
                })->implode('');

                return response()->json($output);
            }

            return response()->json($output);
        }
    }


}

