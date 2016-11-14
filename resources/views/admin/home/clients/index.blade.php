@extends('admin.layouts.app')
@section('content')
    <div id="page-wrapper">
        <div class="container-fluid">
            <h3>Our clients</h3>
            <div class="pull-right">
                <a href="{{ route('admin.home.infolists.add','clients') }}" class="btn btn-primary"><i class="fa fa-plus"></i> Add</a>
            </div>
            <table>
                <thead>

                </thead>
                <tbody>
                @if ($lists->count())
                    @foreach ($lists as $item)
                        <tr>
                            <td>{{$item->name}}</td>
                            <td><input type="number" value="{{$item->order }}"/></td>
                            <td>
                                <button class="btn btn-info" href="{{route('admin.home.infolists.edit',['id'=>$item->id])}}">edit</button>
                                <button class="btn btn-danger" onclick="return removeConfirm(this);">delete</button>
                            </td>
                        </tr>

                    @endforeach
                @else
                    <tr>
                        <td colspan="5">
                            There no items.
                        </td>
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
