@extends('admin.home.el.infolist_form')
@section('form')
        <div class="form-group">
            <label class="form-label">Name</label>
            <input class="form-control" name="name" value="{{object_get($item,'name',old('name')) }}"/>
        </div>

        <div class="form-group">
            <label class="form-label">Weight</label>
            <input class="form-control" name="weight" value="{{ object_get($item,'weight',old('weight')) }}"/>
        </div>

        <div class="form-group">
            <label class="form-label">Image</label>
            @if ($item)
                <img src="{{$item->image}}" class="img-responsive" height="50px"/>
            @endif
            <input type="file" name="image" />
        </div>
@endsection
