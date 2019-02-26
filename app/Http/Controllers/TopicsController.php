<?php

namespace App\Http\Controllers;

use App\Handlers\ImageUploadHandler;
use App\Models\Category;
use App\Models\Link;
use App\Models\Topic;
use App\Models\User;
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

	public function index(Request $request,Topic $topic,User $user,Link $link)
	{
		$topics = $topic->withOrder($request->order)->paginate(20);

		$active_users = $user->getActiveUsers();

		$links = $link->getAllCached();

		return view('topics.index', compact('topics','active_users','links'));
	}

    public function show(Request $request,Topic $topic)
    {
		// URL 矫正
		//我们需要访问用户请求的路由参数 Slug，在 show() 方法中我们注入 $request；
		//! empty($topic->slug) 如果话题的 Slug 字段不为空；
		//&& $topic->slug != $request->slug 并且话题 Slug 不等于请求的路由参数 Slug；
		//redirect($topic->link(), 301) 301 永久重定向到正确的 URL 上。
		if(!empty($topic->slug) && $topic->slug != $request->slug)
		{
			return redirect($topic->link(),301);
		}

        return view('topics.show',compact('topic'));
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
		return redirect()->to($topic->link())->with('success', '话题创建成功!');
	}

	//编辑页面
	public function edit(Topic $topic)
	{
        $this->authorize('update', $topic);
		$categorys = Category::all();
		return view('topics.create_and_edit', compact('topic','categorys'));
	}

	//编辑页面数据提交
	public function update(TopicRequest $request, Topic $topic)
	{
		$this->authorize('update', $topic);
		$topic->update($request->all());

		return redirect()->to($topic->link())->with('success', '话题更新成功!');
	}

	public function destroy(Topic $topic)
	{
		$this->authorize('destroy', $topic);
		$topic->delete();

		return redirect()->route('topics.index')->with('message', 'seccess 话题删除成功!.');
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