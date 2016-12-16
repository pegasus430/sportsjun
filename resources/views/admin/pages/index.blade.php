@extends('admin.layouts.app')
@section('content')
    <div id="page-wrapper">
        <div class="container-fluid">
            <h3>Pages</h3>
            <div class="pull-right">
                <a href="{{ route('admin.pages.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> Add</a>
            </div>
            <table class="table table-striped table-responsive">
                <thead>
                <tr>
                    <th>Name</th>
                    <th></th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @if ($pages->count())
                    @foreach ($pages as $page)
                        <tr>
                            <td>{{$page->title}}</td>
                            <td></td>
                            <td>
                                <a href="{{route('admin.pages.edit',['id'=>$page->id])}}" class="btn btn-info" >edit</a>
                                <button class="btn btn-danger"
                                        data-url="{{route('admin.pages.delete',[$page->id])}}"
                                        data-title="Pages"
                                        data-confirm="Delete {title}"
                                        onclick="return removeConfirm(this);">X</button>    </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="2">There no page exists</td>
                    </tr>
                @endif

                </tbody>
            </table>


        </div>
    </div>
    <script>
        $('#page-wrapper').css('margin', '0px');
    </script>
@endsection
