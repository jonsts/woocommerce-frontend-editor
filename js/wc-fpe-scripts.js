jQuery(document).ready(function($) {
    const dialog = document.getElementById('wc-fpe-modal');

    // Check for dialog support and apply polyfill if necessary
    if (!dialog.showModal) {
        dialogPolyfill.registerDialog(dialog);
    }

    // Open the modal when the edit icon is clicked
    $('#wc-fpe-open-modal').on('click', function(e) {
        e.preventDefault();
        dialog.showModal();
    });

    // Close the modal when the close button is clicked
    $('#wc-fpe-close').on('click', function() {
        dialog.close();
        $('#wc-fpe-message').html('');
    });

    // Handle form submission
    $('#wc-fpe-form').on('submit', function(e) {
        e.preventDefault();

        // Trigger the save function of the editors to ensure content is updated
        if (typeof tinyMCE !== 'undefined') {
            tinyMCE.triggerSave();
        }

        // Show spinner
        $('#wc-fpe-spinner').css('display', 'inline-block');

        // Disable Save button
        $('#wc-fpe-submit').prop('disabled', true);

        var data = {
            action: 'wc_fpe_save_product',
            wc_fpe_nonce: $('#wc-fpe-form [name="wc_fpe_nonce"]').val(),
            wc_fpe_post_id: $('#wc-fpe-form [name="wc_fpe_post_id"]').val(),
            wc_fpe_title: $('#wc-fpe-title').val(),
            wc_fpe_short_desc: $('#wc-fpe-short-desc').val(),
            wc_fpe_long_desc: $('#wc-fpe-long-desc').val(),
        };

        $.post(wc_fpe_ajax.ajaxurl, data, function(response) {
            // Hide spinner
            $('#wc-fpe-spinner').css('display', 'none');

            // Enable Save button
            $('#wc-fpe-submit').prop('disabled', false);

            if (response.success) {
                $('#wc-fpe-message').html('<p style="color: green;">' + response.data + '</p>');
                // Optionally, close the dialog after a delay
                setTimeout(function() {
                    dialog.close();
                    // Optionally reload the page to reflect changes
                    // location.reload();
                }, 2000);
            } else {
                $('#wc-fpe-message').html('<p style="color: red;">' + response.data + '</p>');
            }
        });
    });
});
