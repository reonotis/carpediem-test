<?php

// 管理画面のメインメニュー
function list_of_course(){
    add_menu_page(
        'course',                  //   第1引数　：　ページタイトル（title）,
        'コース一覧',                  //   第2引数　：　メニュータイトル,
        'manage_options',           //   第3引数　：　メニュー表示するユーザーの権限,
        'list_of_course',          //   第4引数　：　メニューのスラッグ,
        'add_list_of_course',      //   第5引数　：　メニュー表示時に使われる関数,
        'dashicons-admin-generic',  //   第6引数　：　メニューのテキスト左のアイコン,
        3                           //   第7引数　：　メニューを表示する位置;
    );
}
function add_list_of_course(){
    $courses = get_courses();
    include 'admin_views/course_list.php';
}
add_action('admin_menu', 'list_of_course');



// 管理画面のサブメニュー
function add_custom_submenu_page_course(){
    add_submenu_page('list_of_course', 'コース登録', 'コース登録', 'manage_options', 'register_course_page', 'add_register_course_menu_page', 2);
    add_submenu_page('list_of_course', 'コース編集', 'コース編集', 'manage_options', 'edit_course_page', 'add_edit_course_menu_page', 5);
}
function add_register_course_menu_page(){
    include 'admin_views/course_create.php';
}
function add_edit_course_menu_page(){
    include 'admin_views/course_edit.php';
}
add_action('admin_menu', 'add_custom_submenu_page_course');












function get_courses(){
	global $wpdb;
	$query="SELECT *
            FROM courses
            WHERE del_flg = 0
            ";
	$results = $wpdb->get_results($query);
    $results = set_displayNames($results);
	return $results;
}


function get_course($id){
	global $wpdb;
	$query="SELECT *
            FROM courses
            WHERE id = $id
            AND del_flg = 0
            ";
	$results = $wpdb->get_row($query);
    // $results = set_displayName($results);
	return $results;
}


function set_displayNames($dates){
    foreach($dates as $date){
        $date = set_displayName($date);
    }
    return $dates;
}

function set_displayName($date){
    if($date->display_flg == 1){
        $date->display_name = "表示";
    }else{
        $date->display_name = "非表示";
    }
    return $date;
}

