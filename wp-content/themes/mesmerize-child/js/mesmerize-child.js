


// Q & A でクリックしたときの動作
jQuery(function(){
    jQuery('.qa_title').on('click', function() {
        var QA_id = jQuery(this).attr('id');
        if(QA_id.indexOf("qa_title")>=0){
            var num = QA_id.substring(QA_id.indexOf("qa_title") + "qa_title".length);
            jQuery('#qa_content' + num).slideToggle(400 ,'swing');
        }
    });
});