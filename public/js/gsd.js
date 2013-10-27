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

        // Public functions -----------------------------------------------

        /**
         * Initialization
         */
        initialize: function()
        {
            console.log("I'm initialized");
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
        }
    };
})();
