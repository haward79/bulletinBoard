$(document).ready(function()
{

    // Dynamically add a link.
    $('#bulletin_input_newLink').on('click', function()
    {
        $('#bulletin_link_container').append('\
            <li>\
            <div style="margin-bottom:10px; border:1px solid silver;">\
                <input class="bulletin_input_textbox" name="bulletin_field_linkName[]" type="text" value="" placeholder="連結名稱" />\
                <input class="bulletin_input_textbox" name="bulletin_field_linkUrl[]" type="text" value="" placeholder="連結網址" />\
            </div>\
            </li>\
        ');
    });

    // Dynamically add a file.
    $('#bulletin_input_newFile').on('click', function()
    {
        $('#bulletin_file_container').append('\
            <li>\
            <div style="margin-bottom:10px; border:1px solid silver;">\
            <input class="bulletin_input_textbox bulletin_field_fileName" name="bulletin_field_fileName[]" type="text" value="" placeholder="檔案名稱" />\
            <input class="bulletin_input_textbox bulletin_field_fileUrl" name="bulletin_field_fileUrl[]" type="file" value="" placeholder="連結網址" />\
            </div>\
            </li>\
        ');
    });

    // Handle form submit event for file upload.
    $('#bulletinEdit_form').on('submit', function(e)
    {
        e.preventDefault();

        // Remove invalid file.
        for(i=0; i<$('.bulletin_field_fileUrl').length; ++i)
        {
            if($($('.bulletin_field_fileName')[i]).val()=="" || $('.bulletin_field_fileUrl')[i].files.length==0)
            {
                $($('.bulletin_field_fileName')[i]).prop('disabled', true);
                $($('.bulletin_field_fileUrl')[i]).prop('disabled', true);
                $($('.bulletin_field_fileName')[i]).css('color', 'red');
            }
        }

        // Check max file size = 1000 MB.
        errorFileList = "";
        for(i=0; i<$('.bulletin_field_fileUrl').length; ++i)
        {
            if($($('.bulletin_field_fileName')[i]).val()!="" && $('.bulletin_field_fileUrl')[i].files.length>0 && $('.bulletin_field_fileUrl')[i].files[0].size>1000000000)
            {
                $($('.bulletin_field_fileName')[i]).prop('disabled', true);
                $($('.bulletin_field_fileUrl')[i]).prop('disabled', true);
                $($('.bulletin_field_fileName')[i]).css('color', 'red');
                errorFileList += $($('.bulletin_field_fileName')[i]).val() + "\n";
            }
        }

        if(errorFileList != "")
            alert("下列檔案因大小超過1GB所以無法上傳：\n" + errorFileList);

        // Remove disabled element from field class.
        for(i=0; i<$('.bulletin_field_fileUrl').length; ++i)
            if($($('.bulletin_field_fileName')[i]).prop('disabled') == true)
                $($('.bulletin_field_fileName')[i]).removeClass('bulletin_field_fileName');

        count = 0;
        for(i=0; i<$('.bulletin_field_fileUrl').length; ++i)
            if($($('.bulletin_field_fileUrl')[i]).prop('disabled') == false)
                ++count;

        if(count > 0)
            alert('開始上傳檔案，請稍後');

        // Lock all fields.
        $('.bulletin_input_textbox').prop('readonly', true);
        $('.bulletin_input_selection').prop('readonly', true);
        $('.bulletin_input_textarea').prop('readonly', true);

        $(this).ajaxSubmit(
        {
            uploadProgress: function(e, uploadedByte, totalByte, completePercent)
            {
                $('.bulletin_field_fileName').css('background-image', 'linear-gradient(to right, #c9c9ee ' + completePercent + '%, transparent ' + completePercent + '%)');
            },

            success: function()
            {
                if(count > 0)
                    alert('檔案上傳完成');

                window.location.href = 'index.php';
            },

            error: function()
            {
                if(count > 0)
                    alert('公告發佈失敗');

                window.location.href = 'index.php';
            }
        });
    });

});

