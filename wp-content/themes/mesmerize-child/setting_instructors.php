<?php

// 管理画面のメインメニュー
function list_of_teacher(){
    add_menu_page(
        'teacher',                  //   第1引数　：　ページタイトル（title）,
        '先生一覧',                  //   第2引数　：　メニュータイトル,
        'manage_options',           //   第3引数　：　メニュー表示するユーザーの権限,
        'list_of_teacher',          //   第4引数　：　メニューのスラッグ,
        'add_list_of_teacher',      //   第5引数　：　メニュー表示時に使われる関数,
        'dashicons-admin-generic',  //   第6引数　：　メニューのテキスト左のアイコン,
        2                           //   第7引数　：　メニューを表示する位置;
    );
}
function add_list_of_teacher(){
    $instructors = get_instructors();
    include 'admin_views/instructor_list.php';
}
add_action('admin_menu', 'list_of_teacher');



// 管理画面のサブメニュー
function add_custom_submenu_page(){
    add_submenu_page('list_of_teacher', '先生登録', '先生登録', 'manage_options', 'register_teacher_page', 'add_register_teacher_menu_page', 1);
    add_submenu_page('list_of_teacher', '先生編集', '先生編集', 'manage_options', 'edit_teacher_page', 'add_edit_teacher_menu_page', 5);
}
function add_register_teacher_menu_page(){
    include 'admin_views/instructor_create.php';
}
function add_edit_teacher_menu_page(){
    include 'admin_views/instructor_edit.php';
}
add_action('admin_menu', 'add_custom_submenu_page');





function get_instructor($id){
	global $wpdb;

	$query="SELECT *
            FROM instructors
            WHERE id = $id
            LIMIT 1
            ";
	$results = $wpdb->get_row($query);
	return $results;
}


function get_instructors(){
	global $wpdb;

	$query="SELECT *
            FROM instructors
            WHERE del_flg = 0
            ";
	$results = $wpdb->get_results($query);
	return $results;
}

/**
 *
 * @return void
 */
function func_show_instructor($atts) {
    $atts = shortcode_atts(array(
        "id" => 1,
    ),$atts);
    $id = 1;
    if($_GET['id']){
        $id = $_GET['id'];
    }

	global $wpdb;
	$query="SELECT *
            FROM instructors
            WHERE id = $id
            AND del_flg = 0
            ";
	$results = $wpdb->get_row($query);

    $titleList = get_instructor_awards($id, true);

    $chargeClasses = get_instructors_courses_mapping($id);

    if(is_null($results)){
        return '表示できるインストラクターはいません';
    }else{
        $HTML = '<div class="instructorSection" >';
            $HTML .= '<div class="instructorArea" >';
                $HTML .= '<div class="instructorImg" >';
                    $HTML .= '<img src="' . $results->img_pass . '">';
                $HTML .= '</div>';
                $HTML .= '<div class="instructorContents" >';
                    $HTML .= '<div class="instructorName" >' . $results->instructor_name . '</div>';
                    $HTML .= '<div class="instructorLevel" >' . $results->instructor_level . '</div>';
                    $HTML .= '<div class="introduction" >' ;
                    if(!empty($results->faceBook_url) || !empty($results->instagram_url) || !empty($results->twitter_url) ){
                        $HTML .= '<div class="snsList" >';
                            if(!empty($results->faceBook_url)){
                                $HTML .= '<a href="' . $results->faceBook_url . '" target="_blank" ><div class="" ><img src="' . get_stylesheet_directory_uri() . '/img/FB_logo.png" ></div></a>';
                            }
                            if(!empty($results->instagram_url)){
                                $HTML .= '<a href="' . $results->instagram_url . '" target="_blank" ><div class="" ><img src="' . get_stylesheet_directory_uri() . '/img/instrgram-150x150.png" ></div></a>';
                            }
                            if(!empty($results->twitter_url)){
                                $HTML .= '<a href="' . $results->instagram_url . '" target="_blank" ><div class="" ><img src="' . get_stylesheet_directory_uri() . '/img/Twitter_Icon-150x150.png" ></div></a>';
                            }
                        $HTML .= '</div>';
                    }
                        $HTML .= '<div class="specialMoveTitle" >自己紹介</div>';
                        $HTML .= '<div class="specialMoveContent" >'. nl2br($results->introduction).'</div>';
                    $HTML .= '</div>';
                    $HTML .= '<div class="specialMove" >';
                        $HTML .= '<div class="specialMoveTitle" >得意技</div>';
                        $HTML .= '<div class="specialMoveContent" >'. nl2br($results->special_move).'</div>';
                    $HTML .= '</div>';
                $HTML .= '</div>';
                if($titleList){
                    $HTML .= '<div class="instructorTitleArea" >';
                        $HTML .= '<div class="instructorTitleWrapper" >主なタイトル</div>';
                        $HTML .= '<ul class="instructorTitleList">';
                            foreach($titleList as $title){
                                $HTML .= '<li>'.$title->award.'</li>';
                            }
                        $HTML .= '</ul>';
                    $HTML .= '</div>';
                }
                if(!empty($chargeClasses)){
                    $HTML .= '<div class="instructorTitleArea" >';
                        $HTML .= '<div class="instructorTitleWrapper" >担当クラス</div>';
                        $HTML .= '<ul class="instructorTitleList">';
                                foreach($chargeClasses as $chargeClass){
                                    $HTML .= '<li>'.$chargeClass->course_name.'</li>';
                                }
                        $HTML .= '</ul>';
                    $HTML .= '</div>';
                }
                $HTML .= '<div class="instructorPrivateLessonArea" >';
                    $HTML .= '<div class="instructorPrivateLessonWrapper" >プライベートレッスン</div>';
                    if($results->lesson_fee){
                        $HTML .= '<div class="privateLessonContent instructorLessonFeeContent " >';
                            $HTML .= '<div class="privateLessonTitle" data-en="Lesson_Fee" >【レッスン費】</div>';
                            $HTML .= '<div class="LessonFeeContent" >1回 : '.number_format($results->lesson_fee).'円</div>';
                            $HTML .= '<div class="LessonFeeContent" >4回パック : '.number_format($results->lesson_fee_4time).'円</div>';
                        $HTML .= '</div>';
                    }
                    $HTML .= '<div class="privateLessonContent">';
                        $HTML .= '<div class="privateLessonTitle" data-en="Lesson_Features" >【レッスンの特徴】</div>';
                        $HTML .= nl2br($results->lesson_features);
                    $HTML .= '</div>';
                $HTML .= '</div>';
            $HTML .= '</div>';
        $HTML .= '</div>';
    }

    return $HTML;
}
add_shortcode('show_instructor', 'func_show_instructor');



/**
 *
 * @return void
 */
function func_show_instructor_gallery($atts) {
    $atts = shortcode_atts(array(
        "id" => 1,
    ),$atts);
    $id = 1;

    if($_GET['id']){
        $id = $_GET['id'];
    }

    $galleries = get_instructor_galleries($id, true);

    $HTML = '<div class="instructorSection" >';
        $HTML .= '<div class="instructorArea" >';
            $HTML = '<div class="instructorGalleryErea" >';
                // $HTML .= '<div class="instructorGalleryWrapper" >ギャラリー</div>';
                $HTML .= '<div class="instructorGalleryContent" >';
                    if($galleries){
                        foreach($galleries as $gallery){
                            $HTML .= '<div class="instructorGalleryImg" ><img src="'. $gallery->img_pass .'"></div>';
                        }
                    }else{
                        $HTML .= '現在画像はありません';
                    }
                $HTML .= '</div>';
            $HTML .= '</div>';
        $HTML .= '</div>';
    $HTML .= '</div>';

    return $HTML;
}
add_shortcode('show_instructor_gallery', 'func_show_instructor_gallery');


