@extends('admin.layouts.app')
@section('content')
    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <ul>
                <?php $i = 0; //TODO: beatify this
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

    <?php $data = object_get($item, 'data', []); ?>
    <div class="form-group">
        <label class="form-label">Title</label>
        <input class="form-control" name="title" value="{{object_get($item,'title',old('title')) }}" required"/>
    </div>

    <div class="form-group">
        <label class="form-label">Template</label>
        {!! Form::select('template_id',$templates,object_get($item,'template_id',old('template_id')),['class'=>'form-control','required'=>'required']) !!}
    </div>

    <div class="form-group">
        <label class="form-label">Slug</label>
        <input class="form-control" name="slug" value="{{object_get($item,'linkname',old('slug')) }}" required/>
    </div>

    <div id="blocks">
        @if ($item)
            @foreach ($item->pageBlocks as $block)
                @include('admin.pages.block.form')
            @endforeach
        @endif
    </div>

    <hr/>

    {!! Form::select('new_block_type',\App\Model\PageBlock::$BLOCK_TYPES,'',['id'=>'new_block_type','class'=>'form-control'])!!}

    <br/>
    <button type="button" class="btn btn-info"
            data-source="{{route('admin.pages.blocks')}}"

            onclick="return PageAddBlock(this);">Add Block
    </button>


    <button type="submit" class="btn btn-success pull-right">{{ $item ? "Save" : "Create" }}</button>

    {!! Form::close() !!}



    <script>
        var new_block = -1;


        function PageToggleBlockInfo(el) {
            var info = $(el).parents('.panel-group').find('.panel-collapse');
            info.collapse("toggle")
            return false;
        }

        function PageAddBlock(el) {
            var block_type = $("#new_block_type").val();
            var source = $(el).data('source') + '?type=' + block_type;
            $.get(source, function (result) {
                var result = result.replace(new RegExp('blocks[\[\]]','g'), 'blocks[' + new_block + ']');
                $("#blocks").append(result);
            });
            new_block--;
        }

        function PageRemoveBlock(el) {
            $(el).parents('.panel-group').remove();
        }

        function MoveBlockDown(el) {
            $(el).parents('panel-group');
        }

        function MoveBlockUp(el) {
            $(el).parents('panel-group');
        }


    </script>
@endsection