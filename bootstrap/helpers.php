<?php

function route_class()
{
    return str_replace('.', '-', Route::currentRouteName());
}

function make_excerpt($value,$length = 200)
{
    //strip_tags — 从字符串中去除 HTML 和 PHP 标记
    //preg_replace — 执行一个正则表达式的搜索和替换
    //trim — 去除字符串首尾处的空白字符（或者其他字符）
    $excerpt = trim(preg_replace('/\r\n|\r|\n+/','',strip_tags($value)));

    //str_limit($excerpt,$length)   ($excerpt)为要处理的字符   ($length)为需要留下的字符长度
    return str_limit($excerpt,$length);
}


function model_admin_link($title,$model)
{
    return model_link($title,$model,'admin');
}

function model_link($title,$model,$prefix = "")
{
    //获取数据模型的复数蛇形命名
    $model_name = model_plural_name($model);

    //初始化前缀
    $prefix = $prefix ? "/$prefix/" : '/';

    //使用站点URL拼接全量URL
    $url = config('app.url') . $prefix . $model_name . '/' . $model->id;

    //拼接 HTML A标签,并返回
    return '<a href="' . $url . '" target="_blank">' . $title . '</a>';
}

function model_plural_name($model)
{
    //从实体中获取完整类名,例如：App\Model\User
    $full_class_name = get_class($model);

    //获取基础类名,例如：传参'App\Models\User'  会得到 'User'
    $class_name = class_basename($full_class_name);

    //蛇形命名,例如：传参'user' 会得到'user','FooBar'会得到'foobar'
    $snake_case_name = snake_case($class_name);

    //获取子串的复数形式,例如：传参'user'会得到'users'
    return str_plural($snake_case_name);
}