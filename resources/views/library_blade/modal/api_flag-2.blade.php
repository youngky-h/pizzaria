<div class="modal fade" id='{{$mdlId}}' tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="mdl_title">{{$mdlTitle}}</h4>
            </div>
            <div class="modal-body">
                <input type="hidden" class="form-control" id="update_flag_id" disabled  />
                <input type="hidden" class="form-control" placeholder="flag" id='update_flag_data' name='update_flag_data'  />
                
                {{$mdlMainInformation}}
                <span id='mdl_body'></span>
                @isset($mdlTableHeader)
                <table id='tbl_update_flag' class='table table-striped table-bordered' cellspacing='0' width='100%'>
                    <thead>
                        <tr>{{$mdlTableHeader}}</tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                @endisset
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                <button id='btn_submit' type="submit" class="btn btn-success submit">Simpan</button>
            </div>
        </div>
    </div>
</div>