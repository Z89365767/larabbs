<?php

namespace App\Http\Controllers;

use App\Handlers\ImageUploadHandler;
use App\Models\Category;
use App\Models\Topic;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\TopicRequest;
use Auth;

class TopicsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }

	public function index(Request $request,Topic $topic)
	{
		$topics = $topic->withOrder($request->order)->paginate(20);
		return view('topics.index', compact('topics'));
	}

    public function show(Topic $topic)
    {
        return view('topics.show', compact('topic'));
    }

	//创建话题页面
	public function create(Topic $topic)
	{
		$categorys = Category::all();

		return view('topics.create_and_edit', compact('topic','categorys'));
	}

	//创建话题页面数据提交
	public function store(TopicRequest $request,Topic $topic)
	{
		//fill 方法会将传参的键值数组填充到模型的属性中
		//$request->all()返回的是数组
		$topic->fill($request->all());
		$topic->user_id = Auth::id();
		$topic->save();
		//$topic = Topic::create($request->all());
		return redirect()->route('topics.show', $topic->id)->with('message', 'Created successfully.');
	}

	//编辑页面
	public function edit(Topic $topic)
	{
        $this->authorize('update', $topic);
		return view('topics.create_and_edit', compact('topic'));
	}

	//编辑页面数据提交
	public function update(TopicRequest $request, Topic $topic)
	{
		$this->authorize('update', $topic);
		$topic->update($request->all());

		return redirect()->route('topics.show', $topic->id)->with('message', 'Updated successfully.');
	}

	public function destroy(Topic $topic)
	{
		$this->authorize('destroy', $topic);
		$topic->delete();

		return redirect()->route('topics.index')->with('message', 'Deleted successfully.');
	}

	public function uploadImage(Request $request,ImageUploadHandler $handler)
	{
		//初始化返回数据,默认是失败的
		$data = [
			'success' => false,
			'msg' => '上传失败!',
			'file_path' => '',
		];

		//判断是否有文件上传,并赋值给$file
		if($request->upload_file)
		{
			//保存图片到本地
			$result = $handler->save($request->upload_file,'topics',\Auth::id(),1024);

			//图片保存成功的话
			if($result)
			{
				$data['file_path'] = $result['path'];
				$data['msg'] = '上传成功!';
				$data['success'] = true;
			}

		}

		return $data;
	}
}