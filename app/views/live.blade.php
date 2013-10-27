@extends("layouts.default")

@section("content")

  {{-- Open Tasks --}}
  <div class="panel panel-primary">
    <div class="panel-heading">Open Tasks</div>
    <div class="panel-body">
      <table class="table table-hover">
        <tbody id="open-tasks">
          <tr><td colspan="3">todo</td></tr>
        </tbody>
      </table>
    </div>
  </div>

  {{-- Completed Tasks --}}
  <div class="panel panel-default">
    <div class="panel-heading">Completed Tasks</div>
    <div class="panel-body">
      <table class="table table-hover">
        <tbody id="completed-tasks">
          <tr><td colspan="3">done</td></tr>
        </tbody>
      </table>
    </div>
  </div>

@stop