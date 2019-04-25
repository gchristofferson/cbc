// ----- Document Ready Functions -----//
$(document).ready(function () {
    // Mark menu link as active for current page.
    $(function() {
        // http(s)
        const protocol = window.location.protocol;
        // get hostname
        const hostname = window.location.hostname;
        let linkStr;
        // get this pages url
        let locStr = location.href;
        let s = locStr;
        let t = locStr;
        let navLink;

        // check if url contains parameters
        if(locStr.indexOf('?') !== -1){
            s = s.substring(0, s.indexOf('?'));
        }

        // check if url contains #
        if(locStr.indexOf('#') !== -1){
            t = t.substring(0, t.indexOf('#'));
        }

        $('.nav-link').each(function() {
            // build url string
            linkStr = protocol + "//";
            linkStr += hostname;
            navLink = $(this).attr('href');
            linkStr += navLink;

            // activate link clicked if matches url string
            if (linkStr === locStr || linkStr === s || linkStr === t) {
                $(this).addClass('active');
                return false;
            }
            linkStr = "";
        });


        // repeat for dropdown items
        $('.dropdown-item').each(function () {
            linkStr = protocol + "//";
            linkStr += hostname;
            navLink = $(this).attr('href');
            linkStr += navLink;
            if (linkStr === locStr || linkStr === s || linkStr === t) {
                // console.log($(this));
                $(this).addClass('active');
                $(this).parent().parent().children(':first-child').addClass('active');
                return false;
            }
        });

        // ----- Make Inquiry ----- //
        let cities = [];
        let selectCounter = 0;

        function countSelect() {
            $('select').each(function () {
                selectCounter ++;
            });
        }

        // count number of select fields
        countSelect();

        // add click handler for first delete btn
        $('.js-delBtn').click(function () {
            if (selectCounter === 1) {
                $('.city-error').css('display', '');
            }
        });


        // add another city field
        $("#add-city").click(function () {

            let msg;

            // if user tries to add another city when previous is blank, alert message
            if (selectCounter === 1) {
                msg = 'Please choose one city before adding another.';
            }  else {
                msg = 'Please select city in previous field before adding another.';
            }
            let selectDiv = $(this).parent('div').prev('.add-select');
            if (selectDiv.children('select').children('option:selected').val() === 'Choose City...') {
                return alert(msg);
            }

            // clone last select element and delete button
            let selectDivClone = selectDiv.clone();


            // insert new after last select element
            $(selectDivClone).insertAfter(selectDiv);

            // increment selectCounter
            selectCounter ++;

            // add click function to delete button
            $('.js-delBtn').click(function () {
                if (selectCounter > 1) {
                    $(this).parent().remove();
                } else if (selectCounter === 1) {
                    $('.city-error').css('display', '');
                }

                selectCounter = 0;
                countSelect();

            });

        });

        // dismiss city error msg
        $('.js-city-error-delete').click(function () {
            $('.city-error').css('display', 'none');
        });

        // add select values to hidden input on submit
        $('#create-inquiry').submit(function() {
            cities = $('select.city').map(function(){
                return this.value
            }).get();

            $("#cities_array").attr('value', [...new Set(cities)]);
        });


        /*----- End Make Inquiry -----*/

        /* ----- User Attachments ----- */
        let numUpload = 1;

        // add click handler for first delete btn
        $('.js-delBtn-2').click(function () {
            $(this).prev('div').children('input').val('');
            $(this).prev('div').children('label').text('Choose file...');
        });


        // add id's to the input and delete buttons
        function addUploadIds () {
            $("input[data-doc='js-doc']").each(function (i) {
                $(this).attr('id', 'doc-' + i ); // add id dynamically to each field
                $(this).next("label").attr('id', 'filename-' + i);
                $(this).next("label").attr('for', 'doc-' + i);
                $(this).parent().next("a").attr('id', 'js-doc-' + i);
                $(this).parent().parent().attr('id', 'doc-div-' + i);

                // add change handler to file upload fields IF NOT DELETING
                updateLabel(i);
            });
        }
        addUploadIds();

        // show filename in label after user selects file
        function updateLabel (i) {
            $('#doc-' + i).change(function () {
                let fileName = $(this).val().replace('C:\\fakepath\\', '');
                $("#filename-" + i).text(fileName);
            })
        }

        // add upload file field
        $('#add-doc').click(function () {

            // get id of last upload div
            let id = (numUpload -1).toString();

            // get last upload div
            let uploadDiv = $("#doc-div-" + id);
            let msg;

            // alert message if user is trying to add more fields without when previous fields are not selected
            if (numUpload === 1) {
                msg = 'Please one document first before adding another.';
            }  else {
                msg = 'Please upload document in previous field before adding another.';
            }
            if ($("#filename-" + id).text() === 'Choose file...') {
                return alert(msg);
            }

            // clone last upload div and delete button
            let uploadDivClone = uploadDiv.clone();

            // update the label
            uploadDivClone.children('div').children('label').text('Choose file...');

            // clear the file value
            uploadDivClone.children('div').children('input').val('');


            // insert new after last uploadDiv
            $( uploadDivClone ).insertAfter( uploadDiv);


            addUploadIds ();

            numUpload ++ ;

            // add click handler for new delete btns
            $('.js-delBtn-2').click(function () {
                $(this).prev('div').children('input').val('');
                $(this).prev('div').children('label').text('Choose file...');
            });

        });

        // dismiss upload error msg
        $('.js-upload-error-delete').click(function () {
            $('.upload-error').css('display', 'none');
        });


        // ----- Upload Avatar -----//
        $("input[name='avatar']").change(function () {
            $("form#user_update").submit();

        });

        // ----- Upload Avatar -----//
        $("input[name='notifications']").change(function () {
            $("form#notifications").submit();

        });

        // ----- Smooth Scrolling ----- //
        // Select all links with hashes
        $('a[href*="#"]')
        // Remove links that don't actually link to anything
            .not('[href="#"]')
            .not('[href="#0"]')
            .click(function(event) {
                // On-page links
                if (
                    location.pathname.replace(/^\//, '') === this.pathname.replace(/^\//, '')
                    &&
                    location.hostname === this.hostname
                ) {
                    // Figure out element to scroll to
                    let target = $(this.hash);
                    target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
                    // Does a scroll target exist?
                    if (target.length) {
                        // Only prevent default if animation is actually gonna happen
                        event.preventDefault();
                        $('html, body').animate({
                            scrollTop: target.offset().top - 115
                        }, 1000, function() {
                            // Callback after animation
                            // Must change focus!
                            let $target = $(target);
                            $target.focus();
                            if ($target.is(":focus")) { // Checking if the target was focused
                                return false;
                            } else {
                                $target.attr('tabindex','-1'); // Adding tabindex for elements not focusable
                                $target.focus(); // Set focus again
                            };
                        });
                    }
                }
            });


        // ----- set checkbox checked attr ----- //
        let checked = ($(':checkbox').prop('checked'));
        $(':checkbox').click(function () {
            if (checked == false) {
                $(this).prop('checked', true);
                $(this).attr('checked', 'checked');
                checked = true;
            } else {
                $(this).prop('checked', false);
                $(this).removeAttr('checked');
                checked = false;
            }
        })

    });
});


// ----- Update User Profile Page ----- //
// redirect to checkout when upgrade button is clicked
$("#upgrade-btn").click(function () {
    window.location.replace("/checkout");
});




