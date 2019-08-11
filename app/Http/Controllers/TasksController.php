<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Task;
use App\User;

class TasksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // まず空の一次配列を用意
        $data = [];
        
        // ログインしてたらユーザとそのタスクを取得して$dataの配列に格納
        if (\Auth::check()) {
            $user = \Auth::user();
            $tasks = $user->tasks()->orderBy('created_at', 'desc')->paginate(10);
            $data = [
                'user' => $user,
                'tasks' => $tasks,
            ];
        }

        // 追記：$dataの配列にタスクの個数をカウントして渡す        
        $data += $this->counts($user);

        //index.blade.phpに取得したuser情報とタスクを渡す
        //indexページをViewとして表示
        return view('tasks.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        /* 変更なし */
        $task = new Task;

        return view('tasks.create', [
            'task' => $task,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // バリデーション
        $this->validate($request, [
            'content' => 'required|max:191',
            'status' => 'required|max:10',
        ]);

        // 追加
        $request->user()->tasks()->create([
            'content' => $request->content,
            'status'  => $request->status,
        ]);

        return redirect('/');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        /* 変更なし */
        $task = Task::find($id);

        return view('tasks.show', [
            'task' => $task,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        /* 変更なし */
        $task = Task::find($id);

        return view('tasks.edit', [
            'task' => $task,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //バリデーション
        $this->validate($request, [
            'content' => 'required|max:191',
            'status' => 'required|max:10',
        ]);
        
        /* 変更なし */
        $task = Task::find($id);
        $task->content = $request->content;
        $task->status = $request->status;
        $task->save();

        return redirect('/');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    
    public function destroy($id)
    {
        // Taskモデルが所有してるタスクのうち、選んだidのタスクを探す
        $task = \App\Task::find($id);

        // 選んだid が 編集中のユーザ（user_id) かどうかを検証
        // 正しければ削除を実行。トップページに戻る。
        if (\Auth::id() === $task->user_id) {
            $task->delete();
        }
        
        return redirect('/');
    }

}
