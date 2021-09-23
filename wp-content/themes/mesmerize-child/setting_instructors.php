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


    if(is_null($results)){
        return '表示できるインストラクターはいません';
    }else{
        $HTML = '<div class="instructorSection" >';
            $HTML = '<div class="instructorArea" >';
                $HTML .= '<div class="instructorImg" >';
                    $HTML .= '<img src="' . $results->img_pass . '">';
                $HTML .= '</div>';
                $HTML .= '<div class="instructorContents" >';
                    $HTML .= '<div class="" >' . $results->instructor_name . '</div>';
                    $HTML .= '<div class="" >' . $results->instructor_level . '</div>';
                    $HTML .= '<div class="" >' . nl2br($results->introduction) . '</div>';
                    if(!empty($results->faceBook_url)){
                        $HTML .= '<div class="" >' . $results->faceBook_url . '</div>';
                    }
                    if(!empty($results->instagram_url)){
                        $HTML .= '<div class="" >' . $results->instagram_url . '</div>';
                    }
                    if(!empty($results->twitter_url)){
                        $HTML .= '<div class="" >' . $results->twitter_url . '</div>';
                    }
                    $HTML .= '<div class="" >' . $results->email . '</div>';
                $HTML .= '</div>';
            $HTML .= '</div>';
        $HTML .= '</div>';
    }

    return $HTML;
}
add_shortcode('show_instructor', 'func_show_instructor');
