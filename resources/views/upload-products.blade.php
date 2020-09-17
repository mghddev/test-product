@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Dashboard') }}</div>
                    <form action="{{route('admin-upload-file')}}" method="POST" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <h2>Upload product csv file</h2>
                        <input type="file" name="products" />
                        <button type="submit" class="btn btn-primary"> تایید </button>
                    </form>


                    <div class="card-body">
                        {{ __('You are logged in!') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
