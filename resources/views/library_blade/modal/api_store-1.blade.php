<div class="modal fade" id={{ $mdlId }} tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="my_modal_label">{{ $mdlTitle }}</h4>
            </div>
            <div class="modal-body">
                <form id='frm_{{ $mdlId }}' method='post' class='form-horizontal form-label-left'>
                {{ $mdlContent }}
                </form>
            </div>                                    
            <div class="modal-footer">
                <button type="button" id='btn_cancel' class="btn btn-default" data-dismiss="modal">Batal</button>
                <button type="submit" id='btn_submit' class="btn btn-success submit">Simpan</button>
            </div>
        </div>
    </div>
</div>
@push('scripts')
 <script type='text/javascript'>
    // attachResetter('{{ $mdlId }}');
    
</script>
@endpush