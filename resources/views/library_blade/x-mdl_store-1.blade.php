<div class="modal fade" id={{ $mdlId }} tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            {{ $mdlFormTag }}
            <div class="modal-header">
                <h4 class="modal-title" id="my_modal_label">{{ $mdlTitle }}</h4>
            </div>
            <div class="modal-body">
                {{ $mdlContent }}
            </div>                                    
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-success submit">Simpan</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>