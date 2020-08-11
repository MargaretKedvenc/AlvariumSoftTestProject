@extends('layouts.app')

@section('content')
    <div class="container">
        @include('layouts.messages')
        <form action="{{ route('import') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="file" name="file" />
            <button type="submit" class="btn btn-success">Submit</button>
        </form>
    </div>
@endsection
