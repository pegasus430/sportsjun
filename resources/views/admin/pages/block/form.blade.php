<?php
$block_id = isset($block) ? $block->id : '';
if (isset($block))
    $type= $block->type;
?>

<div class="panel-group" role="tablist" aria-multiselectable="true">
    <div class="panel panel-default">
        <div class="panel-heading" role="tab" id="headingOne">
            <h4 class="panel-title">
                <a  role="button" class="btn btn-sm pull-right"  onClick="return PageRemoveBlock(this);">
                    <i class="fa fa-remove"></i> Remove
                </a>
                <a role="button"  aria-expanded="false" onClick="return PageToggleBlockInfo(this);">
                    {{$block->type or $type}}: {{ $block->name or 'New' }}
                </a>
                <a role="button" onclick="return MoveBlockUp(this);"><i class="fa fa-caret-square-o-up"></i></a>
                <a role="button" onclick="return MoveBlockDown(this);"><i class="fa fa-caret-square-o-down"></i></a>
            </h4>
        </div>
        <div class="panel-collapse collapse">
            <div class="panel-body">
                <input type="hidden" name="blocks[{{$block_id}}][type]" value="{{$type}}"/>
                <div class="form-group">
                    <label class="form-label">Title</label>
                    <input class="form-control" name="blocks[{{$block_id}}][title]"
                           value="{{$block->title or old('blocks['.$block_id.']_title')}}" required/>
                </div>
                @include('admin.pages.block.types.'.$type)
            </div>
        </div>
    </div>
</div>