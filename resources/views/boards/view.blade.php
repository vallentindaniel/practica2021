@extends('layout.main')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Board view</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{route('boards.all')}}">Boards</a></li>
                        <li class="breadcrumb-item active">Board</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">

        <!-- Default box -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{$board->name}}</h3>
            </div>

            <div class="card-body">
                <select class="custom-select rounded-0" id="changeBoard">
                    @foreach($boards as $selectBoard)
                        <option @if ($selectBoard->id === $board->id) selected="selected" @endif value="{{$selectBoard->id}}">{{$selectBoard->name}}</option>
                    @endforeach
                </select>
                <table class="table table-bordered mt-3">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Assignment</th>
                            <th>Status</th>
                            <th>Date of Creation</th>
                            <th style="width: 40px">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tasks as $task)
                            <tr>
                                <td>{{$task->name}}</td>
                                <td>{{$task->description}}</td>
                                <td>{{$task->user->name ?? 'None'}}</td>
                                <td>
                                    @if($task->status == 0)
                                    created
                                    @elseif($task->status == 1)
                                    in progress
                                    @else
                                    done
                                    @endif
                                </td>
                                <td>{{$task->created_at}}</td>

                                <td>
                                    <div class="btn-group">
                                        <button class="btn btn-xs btn-primary"
                                                type="button"
                                                data-task="{{json_encode($task)}}"
                                                data-user="{{json_encode($users)}}"
                                                data-toggle="modal"
                                                data-target="#taskEditModal">
                                            <i class="fas fa-edit"></i></button>
                                            @if(Auth::user()->role === \App\Models\User::ROLE_ADMIN || Auth::user()->id  == ($task->user->id ?? 0) )
                                        <button class="btn btn-xs btn-danger"
                                                type="button"
                                                data-task="{{json_encode($task)}}"
                                                data-toggle="modal"
                                                data-target="#taskDeleteModal">
                                            <i class="fas fa-trash"></i></button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <!-- /.card -->

    </section>
    <!-- /.content -->

    <div class="modal fade" id="taskEditModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="taskTitle">Edit task</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <div class="alert alert-danger hidden" id="taskEditAlert"></div>

                        <input type="hidden" id="taskEditId" value="" />

                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" class="form-control" name="taskEditName" value="" id="taskEditName" placeholder="Name ...">
                        </div>

                        <div class="form-group">
                            <label>Description</label>
                            <textarea class="form-control" name="taskEditDescription" id="taskEditDescription" rows="4" placeholder="Enter ..."></textarea>
                        </div>

                        <div class="form-group">
                            <label>Assignment</label>
                            <select class="form-control" name="taskEditAssignment" id="taskEditAssignment">
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Status</label>
                            <select class="form-control" name="taskEditStatus" id="taskEditStatus">
                                <option value="0">Created</option>
                                <option value="1">In progress</option>
                                <option value="2">Done</option>
                            </select>
                        </div>

                    </div>

                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="taskEditButton">Save changes</button>
                    </div>
                </div>
            </div>
        </div>
@endsection
