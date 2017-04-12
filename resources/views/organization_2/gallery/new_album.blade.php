<div id = "new_album" class = "modal fade"   tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
     <div class="modal-dialog" role="document">
                <div class="modal-content">
                <form action="/organization/{{$organisation->id}}/album/add" method="post" class="form form-horizontal" id='album_form_add'>
               <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            <h3>New Album</h3> </div>
                        <div class="modal-body">
                        {!! csrf_field() !!}
                            <div class="content">
                                <div class="input-container">
                                    <input type="text" id="staff_name" required="required" name="title" />
                                    <label for="Username">Album Title</label>
                                    <div class="bar"></div>
                                </div>
                              
                             
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Add</button>
                        </div>
                    </form>
            </div>
        </div> {{-- /.modal-dialog --}}
    </div> {{-- /.vertical-alignment-helper --}}
</div> {{-- /.modal --}}