{{-- this form is used for non normal http request/ non-api --}}

<div class="modal fade" id={{ $mdlId??'mdlUpdateFlag' }} tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            {{$mdlFormTag}}
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="mdl_title">{{$mdlTitle??'Title'}}</h4>
            </div>
            <div class="modal-body">
                <input type="hidden" class="form-control" id="update_flag_id" name="update_flag_id"  />
                <input type="hidden" class="form-control" placeholder="flag" id='update_flag_data' name='update_flag_data'  />
                <span id='mdl_action_body'></span>
                @isset($mdlMainInformation)
                    {{$mdlMainInformation}}
                @endisset
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
                <button type="submit" class="btn btn-success submit">Simpan</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>