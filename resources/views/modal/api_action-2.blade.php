<div class="modal fade" id='mdl_action' tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="mdl_title">{{$mdlTitle}}</h4>
            </div>
            <div class="modal-body">
                <input type="hidden" class="form-control" id="_action_target_id" disabled  />
                <input type="hidden" class="form-control" id='_action_name' name='_action_name'  />

                {{$mdlMainInformation}}
                @isset($mdlTableHeader)
                <table id='tbl_action' class='table table-striped table-bordered' cellspacing='0' width='100%'>
                    <thead>
                        <tr>{{$mdlTableHeader}}</tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                @endisset
                <div class='row'>
                    <div class='col-xs-12' id='mdl_body'></div>
                </div>
            </div>
            <div class="modal-footer">
                <button id='btn_close' type="button" class="btn btn-default" data-dismiss="modal">Tidak</button>
                <button id='btn_submit' type="submit" class="btn btn-success submit">Ya</button>
            </div>
        </div>
    </div>
</div>
@push('scripts')
 <script type='text/javascript'>
    $('#mdl_action #btn_submit').click(function(){
        doAction();
    });
</script>
@endpush
