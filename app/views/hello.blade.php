<!DOCTYPE html>
<html>
  <head>
    <title>Getting Stuff Done with Laravel</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{ HTML::style('/css/bootstrap.min.css') }}
    {{ HTML::style('/css/bootstrap-theme.min.css') }}
    <style type="text/css">
      body {
        margin-top: 70px;
      }
    </style>
  </head>
  <body>

    {{-- Top Navbar --}}
    <div class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <a class="navbar-brand" href="#">Getting Stuff Done</a>
        </div>
        <ul class="nav navbar-nav">
          <li class="active">
            <a href="#">Actions list</a>
          </li>
        </ul>
        <form class="navbar-form navbar-right">
          <button type="submit" class="btn btn-success">
            <span class="glyphicon glyphicon-plus-sign"></span>
            Add Task
          </button>
        </form>
      </div>
    </div>

    <div class="container">
      <div class="row">
        <div class="col-md-3">

          {{-- Active Lists --}}
          <div class="panel panel-info">
            <div class="panel-heading">Active Lists</div>
            <div class="panel-body">
              <ul class="nav nav-pills nav-stacked">
                <li><a href="#">Inbox <span class="badge">8</span></a></li>
                <li class="active">
                  <a href="#">Actions <span class="badge">2</span></a>
                </li>
                <li><a href="#">Waiting For</a></li>
                <li>
                  <a href="#">Someday/Maybe <span class="badge">3</span></a>
                </li>
                <li>
                  <a href="#">Calendar <span class="badge">16</span></a>
                </li>
                <li><a href="#">GSD <span class="badge">7</span></a></li>
              </ul>
            </div>
          </div>

          {{-- Archived Lists --}}
          <div class="panel panel-default">
            <div class="panel-heading">Archived Lists</div>
            <div class="panel-body">
              <ul class="nav nav-stacked">
                <li><a href="#">Old Stuff</a></li>
                <li><a href="#">More Old Stuff</a></li>
              </ul>
            </div>
          </div>

        </div>
        <div class="col-md-9">

          {{-- Open Tasks --}}
          <div class="panel panel-primary">
            <div class="panel-heading">Open Tasks</div>
            <div class="panel-body">
              <table class="table table-hover">
                <tbody>
                  <tr>
                    <td><span class="label label-success">next</span></td>
                    <td>Learn to fly without mechanical aid</td>
                    <td>
                      <a href="#" class="btn btn-success btn-xs"
                        title="Mark complete">
                        <span class="glyphicon glyphicon-ok"></span>
                      </a>
                      <a href="#" class="btn btn-info btn-xs"
                        title="Edit task">
                        <span class="glyphicon glyphicon-pencil"></span>
                      </a>
                      <a href="#" class="btn btn-warning btn-xs"
                        title="Move task">
                        <span class="glyphicon glyphicon-transfer"></span>
                      </a>
                      <a href="#" class="btn btn-danger btn-xs"
                        title="Delete task">
                        <span class="glyphicon glyphicon-remove-circle"></span>
                      </a>
                    </td>
                  </tr>
                  <tr>
                    <td></td>
                    <td>
                      Make a million dollars playing poker
                      <span class="label label-primary">due Oct-1</span>
                    </td>
                    <td>
                      <a href="#" class="btn btn-success btn-xs"
                        title="Mark complete">
                        <span class="glyphicon glyphicon-ok"></span>
                      </a>
                      <a href="#" class="btn btn-info btn-xs"
                        title="Edit task">
                        <span class="glyphicon glyphicon-pencil"></span>
                      </a>
                      <a href="#" class="btn btn-warning btn-xs"
                        title="Move task">
                        <span class="glyphicon glyphicon-transfer"></span>
                      </a>
                      <a href="#" class="btn btn-danger btn-xs"
                        title="Delete task">
                        <span class="glyphicon glyphicon-remove-circle"></span>
                      </a>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

          {{-- Completed Tasks --}}
          <div class="panel panel-default">
            <div class="panel-heading">Completed Tasks</div>
            <div class="panel-body">
              <table class="table table-hover">
                <tbody>
                  <tr>
                    <td>
                      <span class="label label-default">finished 9/22/13</span>
                    </td>
                    <td>
                      Watch Dr. Who Marathon
                      <span class="label label-info">due Sep-22</span>
                    </td>
                    <td>
                      <a href="#" class="btn btn-default btn-xs"
                        title="Mark not completed">
                        <span class="glyphicon glyphicon-ok"></span>
                      </a>
                      <a href="#" class="btn btn-danger btn-xs"
                        title="Delete task">
                        <span class="glyphicon glyphicon-remove-circle"></span>
                      </a>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

        </div>
      </div>

    </div>

    <!-- Javascript at the end of the page -->
    {{ HTML::script('/js/jquery.js') }}
    {{ HTML::script('/js/bootstrap.min.js') }}
  </body>
</html>