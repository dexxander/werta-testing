<?php

return [
    'required' => 'Medan :attribute diperlukan.',
    'required_if' => 'Medan :attribute diperlukan apabila :other ialah :value.',
    'string' => 'Medan :attribute mestilah teks.',
    'email' => 'Medan :attribute mestilah alamat e-mel yang sah.',
    'unique' => ':attribute telah digunakan.',
    'file' => 'Medan :attribute mestilah fail.',
    'uploaded' => ':attribute gagal dimuat naik.',
    'mimes' => ':attribute mestilah fail jenis: :values.',
    'in' => ':attribute yang dipilih tidak sah.',
    'numeric' => ':attribute mestilah nombor.',
    'min' => [
        'numeric' => ':attribute mestilah sekurang-kurangnya :min.',
        'string' => ':attribute mestilah sekurang-kurangnya :min aksara.',
        'file' => ':attribute mestilah sekurang-kurangnya :min kilobait.',
        'array' => ':attribute mestilah mempunyai sekurang-kurangnya :min item.',
    ],
    'max' => [
        'numeric' => ':attribute tidak boleh melebihi :max.',
        'string' => ':attribute tidak boleh melebihi :max aksara.',
        'file' => ':attribute tidak boleh melebihi :max kilobait.',
        'array' => ':attribute tidak boleh mempunyai lebih daripada :max item.',
    ],
    'attributes' => [
        'name' => 'nama', 'email' => 'e-mel', 'title' => 'tajuk',
        'abstract' => 'abstrak', 'content' => 'kandungan', 'paper_file' => 'fail kertas',
        'visibility' => 'keterlihatan', 'access_type' => 'jenis akses', 'price' => 'harga',
    ],
    'values' => ['access_type' => ['paid' => 'berbayar']],
];
