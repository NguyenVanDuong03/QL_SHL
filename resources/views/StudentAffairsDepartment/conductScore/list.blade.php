@extends('layouts.studentAffairsDepartment')

@section('title', 'Danh sách điểm rèn luyện')

@section('breadcrumb')
    <x-breadcrumb.breadcrumb :links="[
        ['label' => 'Danh sách Điểm rèn luyện', 'url' => 'student-affairs-department.conduct-score.index'],
        ['label' => 'Điểm rèn luyện'],
    ]" />
@endsection

@section('main')
        <h1>test</h1>
@endsection
