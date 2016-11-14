@extends('admin.layouts.app')
@section('content')
@if (count($errors) > 0)
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li> {{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

{!!  Form::open(['method'=> $item ? "PUT":"POST"])  !!}
    {{csrf_field()}}


    @yield('form')
    <button type="submit" class="btn btn-success">{{ $item ? "Save" : "Add" }}</button>

</form>
@endsection