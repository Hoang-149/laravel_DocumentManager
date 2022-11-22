@extends('layouts.app')

@section('content')
@include('layouts.nav')

<div class="container">
    <div class="row justify-content-center  pt-2">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Liệt kê tài liệu</div>

                <div class="card-body">
                    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                    @endif

                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Tên Tài liệu</th>
                                <th scope="col">Hình ảnh</th>
                                <th scope="col">Files</th>
                                <th scope="col">Quản lý</th>

                            </tr>
                        </thead>
                        <tbody>
                            @php $i = 1; @endphp
                            @forelse($documents as $document)
                            <tr>
                                <th scope="row">{{$i++}}</th>
                                <td>{{$document->name}}</td>
                                <td>{{$document->images->count()}} <a href="{{route('document.show',$document->id)}}">View</a></td>
                                <td>
                                    <!-- {{ asset('public/uploads/files/'.$document->files)}} -->
                                </td>
                                <td>
                                    <a href="{{route('document.edit',[$document->id])}}" class="">Edit</a>
                                    <form action="{{route('document.destroy',[$document->id])}}" method="POST">
                                        @method('DELETE')
                                        @csrf
                                        <button onclick="return confirm('Bạn muốn xóa tài liệu này không?')" class="btn btn-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center">Không có tài liệu nào!</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection