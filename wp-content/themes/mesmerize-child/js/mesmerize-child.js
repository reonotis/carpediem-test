


//
jQuery(function(){
    jQuery('.courseScheduleWrapperTitle').on('click', function() {
        var courseScheduleWrapperTitle_id = jQuery(this).attr('id');
        console.log(courseScheduleWrapperTitle_id)
        if(courseScheduleWrapperTitle_id.indexOf("courseScheduleWrapperTitle")>=0){
            var num = courseScheduleWrapperTitle_id.substring(courseScheduleWrapperTitle_id.indexOf("courseScheduleWrapperTitle") + "courseScheduleWrapperTitle".length);
            jQuery('#courseScheduleContent' + num).slideToggle(200 ,'swing');
        }
    });
});



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





