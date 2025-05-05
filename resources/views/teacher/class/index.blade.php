@extends('layouts.teacher')

@section('title', 'Lớp học')

@section('breadcrumb')
    <x-breadcrumb.breadcrumb :links="[['label' => 'Lớp học']]" />
@endsection

@section('main')
    <h1>Trang chủ lớp học</h1>
@endsection
