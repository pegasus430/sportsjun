<div id = "new_photo" class = "modal fade"   tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
     <div class="modal-dialog" role="document">
                <div class="modal-content">
                <form action="/organization/{{$organisation->id}}/photo/add" method="post" class="form form-horizontal" enctype="multipart/form-data">
               <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            <h3>New Photo</h3> </div>
                        <div class="modal-body">
                        {!! csrf_field() !!}
                            <div class="content">
                              
                            <div class="input-container file">
                                    <label>Image</label>
                                    <input type="file" id="staff_email" required="" name="image" accept='image/png,image/jpg,image/jpeg' /> </div>

                                <div class="input-container select">
                                    <div>
                                        <label>Choose Album</label>
                                   {!! Form::select('album_id',$album_select, null, [
                                    'class' => '','id'=>'album_select'
                                ]) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </form>
            </div>
        </div> {{-- /.modal-dialog --}}
    </div> {{-- /.vertical-alignment-helper --}}
</div> {{-- /.modal --}}