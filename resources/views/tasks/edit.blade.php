@extends('layouts.app')

@section('content')

    <h1>id: {{ $task->id }} のタスク編集ページ</h1>

    <div class="row">
        <div class="col-6">
            {!! Form::model($task, ['route' => ['tasks.update', $task->id], 'method' => 'put']) !!}
            
                <div class="form-group">
                    {!! Form::label('status', 'ステータス:') !!}
                    {!! Form::text('status', null, ['class' => 'form-control']) !!}
                </div>            
        
                <div class="form-group">
                    {!! Form::label('content', 'タスク:') !!}
                    {!! Form::text('content', null, ['class' => 'form-control']) !!}
                </div>
        
                <!--↓追記（選択したタスクのidとuser_id(ユーザ)が一致しないと更新ボタンが表示されない）-->
                @if (Auth::id() == $user->id)
                    {!! Form::open(['route' => 'tasks.update']) !!}    
                        {!! Form::submit('更新', ['class' => 'btn btn-light']) !!}
                    {!! Form::close() !!}
                @endif
        </div>
    </div>

@endsection