<div class="modal fade" id="listbox">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">
          &times;
        </button>
        <h4 class="modal-title" id="listbox-title">title</h4>
      </div>
      <div class="modal-body">
        <form class="form-horizontal">
          <div class="form-group">
            <label class="col-lg-3 control-label" for="list-id">
              List Name
            </label>
            <div class="col-lg-9">
              <input type="text" class="form-control" id="list-id">
            </div>
          </div>
          <div class="form-group">
            <label class="col-lg-3 control-label" for="list-title">
              List Title
            </label>
            <div class="col-lg-9">
              <input type="text" class="form-control" id="list-title">
            </div>
          </div>
          <div class="form-group">
            <label class="col-lg-3 control-label" for="list-subtitle">
              List Subtitle
            </label>
            <div class="col-lg-9">
              <input type="text" class="form-control" id="list-subtitle">
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">
          Close
        </button>
        <button type="button" class="btn btn-primary"
          onclick="gsd.listboxSave()">Save</button>
      </div>
    </div>
  </div>
</div>