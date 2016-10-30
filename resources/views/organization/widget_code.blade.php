@extends('layouts.app')
@section('content')
    @include ('teams.orgleftmenu')
    <div id="content" class="col-sm-10" style="height: 986px;">
        <div class="col-sm-9 tournament_profile">
            <div class="form-header header-primary">
                <h4>WIDGET {{-- trans('message.organization.fields.editheading') --}}</h4>
            </div>
            <p>Copy text below</p>
            <textarea  cols="60" rows="10" class="summernote"><iframe src="{{route('widget.organization.info',$id)}}" width="100%" height="100%" style="min-height:500px"></iframe>
            </textarea>
            <form method="POST">
                <input type="hidden" name="organization" value="{{$id}}"/>
                <textarea class="hidden summernote" name="allowed_urls">

                </textarea>
            </form>
            <p>Preview</p>
            <div id="preview" >
                <iframe src="{{route('widget.organization.info',$id)}}" width="100%" height="100%" style="min-height:500px"></iframe>
            </div>
       </div>
    </div>
@endsection
