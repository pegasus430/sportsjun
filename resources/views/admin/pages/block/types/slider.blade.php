<div class="form-group">
    <label class="form-label">Template</label>
    <input class="form-control" name="blocks[{{$block_id}}][data][template]"
           value="{{array_get($block->data,'template', old('blocks['.$block_id.']_data[template]')) }}" required/>
</div>

<div class="form-group">
    <label class="form-label">Content</label>
    <textarea class="form-control" name="blocks[{{$block_id}}][data][content]" rows="5"
              value="{{$block->data or old('blocks['.$block_id.']_data') }}" required></textarea>
</div>
