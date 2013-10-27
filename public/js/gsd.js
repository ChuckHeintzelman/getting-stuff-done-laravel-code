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
     * Handle click on the add task button
     */
    function buttonAddClick()
    {
        gsd.errorMessage("gsd.buttonAddClick() not done");
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

                    // Reload the lists
                    loadLists(false);
                    loadLists(true);
                }
            });
        }
    };
})();
