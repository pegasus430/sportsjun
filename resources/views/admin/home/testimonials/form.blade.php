@extends('admin.home.el.infolist_form')
@section('form')
    <?php $data = object_get($item, 'data', []); ?>
        <div class="form-group">
            <label class="form-label">Name</label>
            <input class="form-control" name="name" value="{{ object_get($item,'name',old('name')) }}"/>
        </div>
        <div class="form-group">
            <label class="form-label">Description</label>
            <textarea rows="10" name="description" class="form-control">{!! object_get($item,'description',old('description')) !!}</textarea>
        </div>

        <div class="form-group">
            <label class="form-label">Weight</label>
            <input class="form-control" name="weight" value="{{ object_get($item,'weight',old('weight')) }}"/>
        </div>

        <div class="form-group">
            <label class="form-label">Image</label>
            @if ($item)
                <img src="{{Helper::getImagePath($item->image,$item->type)}}" class="img-responsive" style="height:100px"/>
            @endif
            <input type="file" name="image" value="{{old('image')}}"/>
        </div>

        <div class="form-group">
            <label class="form-label">Date</label>
            <input placeholder="{{ \Carbon\Carbon::now()->format('Y-m-d H:i') }}" type="datetime" class="form-control" name="data[date]" value="{{  array_get($data,'date',old('data.date')) }}"/>
        </div>
@endsection
