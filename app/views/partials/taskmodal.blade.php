<div class="modal fade" id="taskbox">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">
          &times;
        </button>
        <h4 class="modal-title" id="taskbox-title">title</h4>
      </div>
      <div class="modal-body">
        <form class="form-horizontal">
          <input type="hidden" id="task-index" value="-1">
          <div class="form-group">
            <div class="col-lg-offset-2 col-lg-10">
              <div class="checkbox">
                <label>
                  <input type="checkbox" id="task-next"> Next Action
                </label>
              </div>
            </div>
          </div>
          <div class="form-group">
            <label class="col-lg-2 control-label" for="task-descript">
              Description
            </label>
            <div class="col-lg-10">
              <input type="text" class="form-control" id="task-descript">
            </div>
          </div>
          <div class="form-group">
            <label class="col-lg-2 control-label" for="task-due">
              Due
            </label>
            <div class="col-lg-10">
              <input type="text" class="form-control" id="task-due">
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">
          Close
        </button>
        <button type="button" class="btn btn-primary"
          onclick="gsd.taskboxSave()">Save</button>
      </div>
    </div>
  </div>
</div>