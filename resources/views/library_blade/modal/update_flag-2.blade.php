{{-- 
    this form is used for normal http request/ non-api 
    ver: 2.20180402
--}}

<div class="modal fade" id="mdl_update_flag" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class='modal-content'>
        {{$mdlFormOpen}}
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="mdl_action_title"></h4>
            </div>        
            <div class="modal-body">
                {{$mdlContent}}
                <span id='mdl_action_body'></span>
                <input type="hidden" class="form-control" id="update_flag_id" name="update_flag_id"  />
                <input type="hidden" class="form-control" placeholder="flag" id='update_flag_data' name='update_flag_data'  />     
            </div>                                    
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-success submit">Simpan</button>
            </div>
        </form>
        </div>
    </div>
</div>