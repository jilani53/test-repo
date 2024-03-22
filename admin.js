;(function($) {

    $(document).ready(function () {

        //alert("hello");

        // Video thumbnail processing
        $( "#upload_thumbnail" ).on( "click", function () {

            var frame;

            if ( frame ) {
                frame.open();
                return false;
            }

            frame = wp.media({
                title: "Upload Memeber Image",
                button: {
                    text: "Upload Memeber Image",
                },
                multiple: false,
            });

            frame.on( "select", function () {
                var attachment = frame.state().get("selection").first().toJSON();
                $("#member_photo_id").val(attachment.id);
                $("#member_photo_url").val(attachment.url);
                $("#thumbnail_display").html(`
                    <img src="${attachment.url}" />
                `);
            });

            frame.open();
            return false;
        });

    });

})(jQuery);