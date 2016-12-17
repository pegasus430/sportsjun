<div class="form-group">
    <label class="form-label">Content</label>
    <textarea class="form-control" name="blocks[{{$block_id}}][data]" rows="5" required>{{$block->data or old('blocks['.$block_id.']_data') }}</textarea>
</div>
