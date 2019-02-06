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