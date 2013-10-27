/* Getting Stuff Done Javascript */

/**
 * Fire after page loads
 */
$(function()
{
    gsd.initialize();
});

/**
 * gsd object
 */
var gsd = (function()
{
    // Private vars and functions -----------------------------------------

    var alertTimer = null;
    var currentList = null;
    var activeLists = null;
    var archivedLists = null;

    /**
     * Display a message or error box
     * @param string msgType Either "success" or "error"
     * @param string message The message
     */
    function commonBox(msgType, message)
    {
        clearTimeout(alertTimer);

        $("#message-area").html(
            $("#" + msgType + "-message")
                .html()
                .replace(msgType + '-message-text', message)
                .replace(msgType + '-message-id', 'alert-id')
            );
        alertTimer = setTimeout(function()
        {
            $("#alert-id").alert("close");
            alertTimer = null;
        }, 8000);
    }

    /**
     * Handle click on the top nav menu archive/unarchive option
     */
    function menuArchiveClick()
    {
        gsd.errorMessage("gsd.menuArchiveClick() not done");
        return false;
    }

    /**
     * Handle click on the top nav menu rename option
     */
    function menuRenameClick()
    {
        gsd.errorMessage("gsd.menuRenameClick() not done");
        return false;
    }

    /**
     * Handle click on the top nav menu create option
     */
    function menuCreateClick()
    {
        gsd.errorMessage("gsd.menuCreateClick() not done");
        return false;
    }

    /**
     * Display the task modal box
     * @param string title Title of the modal box
     * @param integer index Task index, -1 for new task
     */
    function taskboxShow(title, index)
    {
        var task = (index == -1) ? {} : currentList.tasks[index];
        $("#task-index").val(index);
        $("#taskbox-title").text(title);
        $("#task-next").prop("checked", (task.isNext === true));
        $("#task-descript").val(task.descript);
        if (task.dateDue)
        {
            var d = new Date(task.dateDue);
            $("#task-due").val(d.toDateString());
        }
        else
        {
            $("#task-due").val("");
        }
        $("#taskbox")
            .modal("show")
            .on("shown.bs.modal", function()
            {
                $("#task-descript").focus().select();
            });
    }

    /**
     * Handle click on the add task button
     */
    function buttonAddClick()
    {
        taskboxShow("Add New Task", -1);
        return false;
    }

    /**
     * Update the navbar for the current list
     */
    function updateNavBar()
    {
        $("#list-name").html("+" + currentList.name);
        $("#menu-archive-text").html(
            currentList.archived ? "Unarchive" : "Archive"
        );
        $("#button-add").prop("disabled", currentList.archived);
    }

    /**
     * Show one of the list of lists on the sidebar
     */
    function showSidebarList(archived)
    {
        var list = archived ? archivedLists : activeLists;
        var ul = archived ? $("#archived-lists") : $("#active-lists");
        var build = [];

        // No items in list of lists?
        if (list.length == 0)
        {
            ul.html('<li>No archived lists</li>');
            return;
        }

        // Loop through each item, building html for the
        for (var i = 0; i < list.length; i++)
        {
            var html = '<li';
            var l = list[i];
            var numTasks = l.numNextActions + l.numNormal;
            if (archived == currentList.archived && l.name == currentList.name)
                html += ' class="active"';
            html += '><a href="javascript:gsd.loadList(\'' + l.name + '\',';
            html += archived + ')">';
            html += l.title;
            if ( ! archived && numTasks > 0)
            {
                html += ' <span class="badge">' + numTasks + '</span>';
            }
            html += '</a></li>';
            build.push(html);
        }
        ul.html(build.join("\n"));
    }

    /**
     * Load the list of lists
     * @param bool archived Load the archived lists?
     */
    function loadLists(archived)
    {
        var url = "/lists";
        if (archived) url += "?archived=1";
        $.ajax({
            url: url,
            error: function(hdr, status, error)
            {
                gsd.errorMessage("loadLists " + status + ' - ' + error);
            },
            success: function(data)
            {
                if (data && data.error)
                {
                    gsd.errorMessage("loadList error: " + data.error);
                    return;
                }
                if (archived)
                {
                    archivedLists = data.lists;
                }
                else
                {
                    activeLists = data.lists;
                }
                showSidebarList(archived);
            }
        });
    }

    /**
     * Build table row html for complete task
     * @param object task Task object
     * @param int index Index of task within currentList.tasks
     * @return string HTML for a table row representing the task
     */
    function buildCompletedTask(task, index)
    {
        var html = [];
        html.push('<tr>');
        html.push('<td><span class="label label-default">finished ');
        var d = new Date(task.dateCompleted);
        html.push(d.toDateString() + '</span></td><td>');
        html.push($('<div/>').text(task.descript).html());
        if (task.dateDue)
        {
            d = new Date(task.dateDue);
            html.push(' <span class="label label-info">');
            html.push('due ' + d.toDateString());
            html.push('</span>');
        }
        html.push('</td><td>');

        if ( ! currentList.archived)
        {
            html.push('<a href="javascript:void(0)" onclick="gsd.doDone(' + index);
            html.push(')" class="btn btn-default btn-xs" title="Mark not complete">');
            html.push('<span class="glyphicon glyphicon-ok"></span></a>');
            html.push(' <a href="javascript:void(0)" onclick="gsd.doDelete(' + index);
            html.push(')" class="btn btn-danger btn-xs" title="Delete task">');
            html.push('<span class="glyphicon glyphicon-remove-circle"></span></a>');
        }

        html.push('</td>');
        html.push('</tr>');

        return html.join('');
    }

    /**
     * Build table row html for open task
     * @param object task Task object
     * @param int index Index of task within currentList.tasks
     * @return string HTML for a table row representing the task
     */
    function buildOpenTask(task, index)
    {
        var html = [];
        html.push('<tr>');
        html.push('<td>');
        if (task.isNext)
            html.push('<span class="label label-success">next</span>');
        html.push('</td>');
        html.push('<td>');
        html.push($('<div/>').text(task.descript).html());
        if (task.dateDue)
        {
            var d = new Date(task.dateDue);
            html.push(' <span class="label label-primary">');
            html.push('due ' + d.toDateString());
            html.push('</span>');
        }
        html.push('</td>');
        html.push('<td>');
        if ( ! currentList.archived)
        {
            html.push('<a href="javascript:void(0)" onclick="gsd.doDone(' + index);
            html.push(')" class="btn btn-success btn-xs" title="Mark complete">');
            html.push('<span class="glyphicon glyphicon-ok"></span></a>');
            html.push(' <a href="javascript:void(0)" onclick="gsd.doEdit(' + index);
            html.push(')" class="btn btn-info btn-xs" title="Edit task">');
            html.push('<span class="glyphicon glyphicon-pencil"></span></a>');
            html.push(' <a href="javascript:void(0)" onclick="gsd.doMove(' + index);
            html.push(')" class="btn btn-warning btn-xs" title="Move task">');
            html.push('<span class="glyphicon glyphicon-transfer"></span></a>');
            html.push(' <a href="javascript:void(0)" onclick="gsd.doDelete(' + index);
            html.push(')" class="btn btn-danger btn-xs" title="Delete task">');
            html.push('<span class="glyphicon glyphicon-remove-circle"></span></a>');
        }

        html.push('</td>');
        html.push('</tr>');

        return html.join('');
    }

    /**
     * Show the Open Tasks and Completed Tasks
     */
    function showTasks()
    {
        var open = [];
        var completed = [];
        for (var i = 0; i < currentList.tasks.length; i++)
        {
            var task = currentList.tasks[i];
            if (task.isCompleted)
            {
                completed.push(buildCompletedTask(task, i));
            }
            else
            {
                open.push(buildOpenTask(task, i));
            }
        }
        if (open.length === 0)
            open.push('<tr><td colspan="3">No open tasks</td></tr>');
        if (completed.length === 0)
            completed.push('<tr><td colspan="3">No completed tasks</td></tr>');
        $("#open-tasks").html(open.join("\n"));
        $("#completed-tasks").html(completed.join("\n"));
    }

    /**
     * Save the current list, then reload everything
     * @param string success_msg The message to show on success
     * @param string from Name of method we're called from, for errors
     */
    function saveCurrentList(success_msg, from)
    {
        $.ajax({
            url: "/lists/" + currentList.name,
            method: "PUT",
            data: currentList,
            error: function(hdr, status, error)
            {
                gsd.errorMessage("saveCurrentList " + status + ' - ' +
                    error + ", from " + from);
            },
            success: function(data)
            {
                if (data && data.error)
                {
                    gsd.errorMessage("saveCurrentList error: " +
                        data.error + ", from " + from);
                    return;
                }

                gsd.loadList(currentList.name, currentList.archived);
                gsd.successMessage(success_msg);
            }
        });
    }

    return {

        // Public vars ----------------------------------------------------

        defaultList: null,

        // Public functions -----------------------------------------------

        /**
         * Initialization
         */
        initialize: function()
        {
            // Assign various handlers
            $("#menu-archive").click(menuArchiveClick);
            $("#menu-rename").click(menuRenameClick);
            $("#menu-create").click(menuCreateClick);
            $("#button-add").click(buttonAddClick);

            // Load the default list
            this.loadList(this.defaultList, false);
        },

        /**
         * Display a success message box
         * @param string message The message to display
         */
        successMessage: function(message)
        {
            commonBox("success", message);
        },

        /**
         * Display an error message box
         * @param string message The message to display.
         */
        errorMessage: function(message)
        {
            commonBox("error", message);
        },

        /**
         * Load a list via AJAX and display it
         * @param string name The list name
         * @param boolean archive Is it an archived list?
         */
        loadList: function(name, archived)
        {
            var url = "/lists/" + name;
            if (archived) url += "?archived=1";
            $.ajax({
                url: url,
                error: function(hdr, status, error)
                {
                    gsd.errorMessage("loadList " + status + " - " + error);
                },
                success: function(data)
                {
                    if (data && data.error)
                    {
                        gsd.errorMessage("loadList error: " + data.error);
                        return;
                    }
                    currentList = data.list;
                    updateNavBar();
                    showTasks();

                    // Reload the lists
                    loadLists(false);
                    loadLists(true);
                }
            });
        },

        /**
         * Mark a task as completed
         */
        doDone: function(index)
        {
            // Toggle completion status
            if (currentList.tasks[index].isCompleted)
            {
                currentList.tasks[index].isCompleted = false;
            }
            else
            {
                var d = new Date();
                currentList.tasks[index].isCompleted = true;
                currentList.tasks[index].dateCompleted = d.valueOf();
            }
            saveCurrentList("Task completion updated.", "doDone");
        },

        /**
         * Edit a task
         */
        doEdit: function(index)
        {
            taskboxShow("Edit Task", index);
        },

        /**
         * Move a task
         */
        doMove: function(index)
        {
            gsd.errorMessage("gsd.doMove() not done");
        },

        /**
         * Delete a task
         */
        doDelete: function(index)
        {
            if ( ! confirm("This will permanently destroy the task. Are you sure?"))
            {
                return;
            }

            // Remove the item from currentList
            currentList.tasks.splice(index, 1);

            // And save the list
            saveCurrentList("Task successfully removed.", "doDelete");
        },

        /**
         * Handle adding new tasks or updating existin tasks
         */
        taskboxSave: function()
        {
            var index = parseInt($("#task-index").val());
            var dueDate = $("#task-due").val();
            var task = {
                isNext: $("#task-next").prop("checked"),
                isCompleted: false,
                dateCompleted: null,
                descript: $("#task-descript").val()
            };
            if (dueDate === "")
            {
                dueDate = null;
            }
            else
            {
                try {
                    dueDate = Date.parse(dueDate);
                } catch (err) {
                    dueDate = null;
                }
                if (isNaN(dueDate))
                    dueDate = null;
            }
            task.dateDue = dueDate;
            if (index < 0)
            {
                currentList.tasks.push(task);
            }
            else
            {
                currentList.tasks[index] = task;
            }

            $("#taskbox").modal("hide");
            saveCurrentList("Task successfully saved.", "taskboxSave");
        }

    };
})();
