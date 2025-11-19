@extends('layouts.app')
@section('content')
<div class="container">
    <h3>تعديل بيانات مهندس</h3>

    <form action="{{ route('engineers.update',$engineer) }}" method="POST" enctype="multipart/form-data">
        @csrf

        @include('engineers.form')

        <button class="btn btn-primary">تحديث</button>
    </form>
</div>
@endsection