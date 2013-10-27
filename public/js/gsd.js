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

    return {

        // Public vars ----------------------------------------------------

        defaultList: null,

        // Public functions -----------------------------------------------

        /**
         * Initialization
         */
        initialize: function()
        {
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
                    gsd.successMessage("Cool");
                    console.log(data.list);
                }
            });
        }
    };
})();
