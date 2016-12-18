@extends('admin.layouts.app')
@section('content')
@if (count($errors) > 0)
    <div class="alert alert-danger">
        <ul>
            <?php $i =0; //TODO: beatify this
            ?>
            @foreach ($errors->all() as $error)
                <li> {{ $errors->keys()[$i] . ': '. $error }}</li>
                <?php $i++;?>
            @endforeach
        </ul>
    </div>
@endif


{!!  Form::open(['method'=> $item ? "PUT":"POST",'files'=> true])  !!}
    {{csrf_field()}}


    @yield('form')
    <button type="submit" class="btn btn-success">{{ $item ? "Save" : "Add" }}</button>

</form>
@endsection