<div class="modal fade" id="mdl_update" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            {{ $mdl_form_tag }}
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="my_modal_label">Update Data</h4>
            </div>
            <div class="modal-body">
                {{ $mdl_content }}
            </div>                                    
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-success submit">Simpan</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>