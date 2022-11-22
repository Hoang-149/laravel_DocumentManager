@extends('layouts.app')

@section('content')
@include('layouts.nav')

<div class="container">
    <div class="row justify-content-center pt-2">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Cập nhật tài liệu</div>

                @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <div class="card-body">
                    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                    @endif

                    <form method="POST" action="{{route('document.update',[$doc->id])}}" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Tên tài liệu</label>
                            <input type="text" value="{{$doc->name}}" name="name" class="form-control" placeholder="Tên tài liệu...">
                        </div>
                        <!-- <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Slug danh mục</label>
                            <input type="text" value="{{old('slug_danhmuc')}}" name="slug_danhmuc" class="form-control" id="convert_slug" aria-describedby="emailHelp" placeholder="Slug danh mục...">
                        </div> -->
                        <div class="mb-3">
                            <label for="exampleInputEmail1">Hình ảnh</label>
                            <br>
                            <input type="file" name="image" class="form-control-file">
                            <a href="{{asset('public/uploads/images/'.$doc->images)}}" target="_blank"><img src="{{asset('public/uploads/images/'.$doc->images)}}" height="100" width="70"></a>
                        </div>
                        <div class="mb-3">
                            <label for="exampleInputEmail1">File</label>
                            <br>
                            <input type="file" name="file" class="form-control-file">
                            <img src="{{asset('public/uploads/files/'.$doc->files)}}" height="100" width="70">
                        </div>
                        <!-- <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Kích hoạt</label>
                            <select class="form-select" name="kichhoat" aria-label="Default select example">
                                <option value="0">Kích hoạt</option>
                                <option value="1">Không kích hoạt</option>
                            </select>
                        </div> -->

                        <button type="submit" name="themtailieu" class="btn btn-primary">Thêm</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection