@extends('layouts.app')
@section('content')
<div class="container">

    <h2>Images for document : <span class="text-primary">{{$doc->name}}</span> </h2>
    <a href="" class="btn btn-primary">Go Back</a>
    <div class="row mt-4">
        @foreach ($images as $image)
        <div class="col-md-3">
            <div class="card text-white bg-secondary mb-3" style="max-width: 20rem;">
                <div class="card-body">
                    <img src="{{asset('public/uploads/document_images/'.$image->image)}}" class="card-img-top">
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection