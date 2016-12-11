@extends('admin.layouts.app')
@section('content')
    <div id="page-wrapper">
        <div class="container-fluid">
            <h3>Banner</h3>
            <div class="pull-right">
                <button class="btn btn-primary" onclick="SaveOrder();return false;">Save Order</button>
                <a href="{{ route('admin.home.infolists.add','banners') }}" class="btn btn-primary"><i
                            class="fa fa-plus"></i> Add
                </a>
            </div>
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Image</th>
                    <th>Weight</th>
                    <th>Enabled</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @if ($lists->count())
                    @foreach ($lists as $item)
                        <tr>
                            <td>{{$item->name}}</td>
                            <td><img src="{{Helper::getImagePath($item->image,$item->type)}}" class="img-responsive"
                                     style="height:100px"/></td>
                            <td>
                                <input type="text" class="form-control weight_data" placeholder="Order"
                                       data-id="{{ $item->id }}"
                                       value="{{ $item->weight }}"/>
                            </td>
                            <td>
                                {{ $item->active ? "Yes":"No" }}
                            </td>
                            <td>
                                <a class="btn btn-info" href="{{route('admin.home.infolists.edit',['id'=>$item->id])}}">edit</a>
                                <button class="btn btn-danger"
                                        data-url="{{route('admin.home.infolists.delete',[$item->id])}}"
                                        data-title="Banner"
                                        data-confirm="Delete {title}"
                                        onclick="return removeConfirm(this);">X</button>
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

        function SaveOrder() {
            var weight_data = $(".weight_data");
            var size = weight_data.length;
            var data = {};
            for (var i = 0; i < size; i++)
                data["weight_data[" + i + "]"] = {
                    id: $(weight_data[i]).data("id"),
                    val: weight_data[i].value
                };
            data["_token"] = "{{  csrf_token() }}";
            $.ajax({
                type: "POST",
                url: "{{ route('admin.home.infolists.order','banners')}}",
                data: data,
                success: function (response) {
                    alert("Order saved");
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    alert(xhr.responseText);
                },
                dataType: "JSON",
            });
        }


    </script>
@endsection
