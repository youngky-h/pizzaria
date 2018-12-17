<div class="modal fade" id="mdl_update" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            {{ $mdl_form_tag }}
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="my_modal_label">New Data</h4>
            </div>
            <div class="modal-body">
                <div class='form-group'>
                    <input type="hidden" class="form-control" placeholder="ID" name='update_id'/>
                    <label>School Name</label>
                    <div class='form-group'>
                        <select id='update_cbo_school_id' name='update_cbo_school_id' class='form-control'></select>
                    </div>
                </div>
                <div class='form-group'>
                    <label>Class Name</label>
                    <input type="text" class="form-control" placeholder="Class Name" name='update_name' required />
                </div>                
                <div class='form-group'>
                    <label>Homeroom Teacher</label>
                    <input type="text" class="form-control" placeholder="School Name" name='update_school_account' disabled />
                </div>
            </div>                                    
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-success submit">Simpan</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>