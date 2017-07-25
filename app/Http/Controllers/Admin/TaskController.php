<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Task;
use Illuminate\Support\Facades\Validator;

class TaskController extends BaseController
{

    protected $command_types = [1 => 'laravel命令', 2 => '系统命令'];
    protected $is_runs = [0 => '关闭任务', 1 => '开启任务'];
    protected $is_wols = [0 => '不允许', 1 => '允许'];
    protected $is_eimms = [0 => '否', 1 => '是'];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //定义每页显示几条
        $page_sizes = ['10' => 10, '25' => 25, '50' => 50, '100' => 100];
        isset($request->page_size) ? $page_size = $request->page_size : $page_size = 10;
        //条件
        $where_str = $request->where_str;
        $where = array();

        if (isset($where_str)) {
            $where[] = ['name', 'like', '%' . $where_str . '%'];

        }
        //分页
        $tasks = Task::where($where)->paginate($page_size);
        //视图
        $command_types = $this->command_types;
        $is_runs = $this->is_runs;
        $is_wols = $this->is_wols;
        return view('admin.task.index', compact(['tasks', 'where_str', 'page_size', 'page_sizes', 'command_types', 'is_runs', 'is_wols']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        return view('admin.task.create', [
            'command_types' => $this->command_types,
            'is_wols' => $this->is_wols,
            'is_eimms' => $this->is_eimms,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $this->validator($request);
        if (Task::create($this->form_arr($request))
        ) {
            return redirect()->back()->with('success', '添加成功');
        } else {
            return redirect()->back()->with('error', '添加失败');
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $task = Task::findOrFail($id);


        return view('admin.task.create', [
            'task'=>$task,
            'command_types' => $this->command_types,
            'is_wols' => $this->is_wols,
            'is_eimms' => $this->is_eimms,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Task $task)
    {
        $this->validator($request);
        //todo
        //数据不变，不更新
        if ($task->update($this->form_arr($request))
        ) {
            return redirect()->back()->with('success', '更新成功');
        } else {
            return redirect()->back()->with('error', '更新失败');
        }



    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    //todo,校验规则未完善
    protected function validator(Request $request)
    {
        return Validator::make($request->all(), [
            'name' => 'required|string',
            'minute' => 'nullable|string',
            'hour' => 'nullable|string',
            'day' => 'nullable|string',
            'month' => 'nullable|string',
            'week' => 'nullable|string',
            'command_type' => 'required',
            'command' => 'required|string',
            'is_wol' => 'required',
            'is_eimm' => 'required',
        ])->validate();
    }

    public function run($id)
    {
        $task = Task::find($id);
        if ($task) {
            if($task->is_run){
                $task->is_run = 0;
            }else{
                $task->is_run = 1;
            }
            $task->save();
            return response()->json([
                'msg' => 1
            ]);

        } else {
            return response()->json([
                'msg' => 0
            ]);
        }

    }
    protected function form_arr(Request $request){
        return [
            'name' => $request->name,
            'minute' => empty($request->minute) ? '*' : $request->minute,
            'hour' => empty($request->hour) ? '*' : $request->hour,
            'day' => empty($request->day) ? '*' : $request->day,
            'month' => empty($request->month) ? '*' : $request->month,
            'week' => empty($request->week) ? '*' : $request->week,
            'command_type' => $request->command_type,
            'command' => $request->command,
            'is_wol' => $request->is_wol,
            'is_eimm' => $request->is_eimm,
            'description' => $request->description,
        ];


    }
}
