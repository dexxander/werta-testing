<?php

return [
    'required' => ':attribute为必填项。',
    'required_if' => '当:other为:value时，:attribute为必填项。',
    'string' => ':attribute必须为文本。',
    'email' => ':attribute必须为有效的电子邮箱地址。',
    'unique' => ':attribute已被使用。',
    'file' => ':attribute必须为文件。',
    'uploaded' => ':attribute上传失败。',
    'mimes' => ':attribute必须为以下类型的文件：:values。',
    'in' => '所选的:attribute无效。',
    'numeric' => ':attribute必须为数字。',
    'min' => [
        'numeric' => ':attribute不得小于 :min。',
        'string' => ':attribute不得少于 :min 个字符。',
        'file' => ':attribute不得小于 :min KB。',
        'array' => ':attribute必须至少包含 :min 项。',
    ],
    'max' => [
        'numeric' => ':attribute不得大于 :max。',
        'string' => ':attribute不得超过 :max 个字符。',
        'file' => ':attribute不得大于 :max KB。',
        'array' => ':attribute不得超过 :max 项。',
    ],
    'attributes' => [
        'name' => '姓名', 'email' => '电子邮箱', 'title' => '标题',
        'abstract' => '摘要', 'content' => '内容', 'paper_file' => '论文文件',
        'visibility' => '可见性', 'access_type' => '访问类型', 'price' => '价格',
    ],
    'values' => ['access_type' => ['paid' => '付费']],
];
