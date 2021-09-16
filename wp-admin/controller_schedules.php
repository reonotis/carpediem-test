<?php

/** WordPress Administration Bootstrap */
require_once __DIR__ . '/admin.php';

class Schedules {

	public function register( ) {
		try {

			// リクエストパラメータのセットする
			$this->set_param();

			// エラーチェックを行う
			$err_lists = $this->check_validation();
			if($err_lists) throw new \Exception();

			// DB登録処理
			global $wpdb;
			$wpdb->insert(
					'schedules',
				array(
					'course_id'     => $this->_course_id,
					'week'         => $this->_week,
					'time'         => $this->_start_time,
					'section'      => $this->_section,
					'display_flg'  => $this->_display_flg,
				// ),array(
				// 	'%d', //instructor_id
				// 	'%s', //comment
				)
			);

			// 並び順を決めておく
			$ID = $wpdb->insert_id;
			$wpdb->update(
				'courses',
				array(
					'sort' => $ID
				),
				array( 'ID' =>  $ID ),
			);

			add_settings_error( 'settings_errors', 'settings_errors', '登録が完了しました。', 'success' );
			// add_settings_error()した内容を、DBに一時保存する
			set_transient( 'settings_errors', get_settings_errors(), 30 );

			// Redirect back to the settings page that was submitted.
			$goback = add_query_arg( 'settings-updated', 'true', './admin.php?page=list_of_schedule' );
			wp_redirect( $goback );
			exit;

		} catch (\Exception $e) {
			// If no settings errors were registered add a general 'updated' message.
			foreach($err_lists as $err_list){
				add_settings_error( 'settings_errors', 'settings_errors', $err_list, 'error' );
			}

			// add_settings_error()した内容を、DBに一時保存する
			set_transient( 'settings_errors', get_settings_errors(), 30 );

			$date = array(
				'settings-updated' => true,
				'course_id'      => $this->_course_id,
				'week'           => $this->_week,
				'start_time'     => $this->_start_time,
				'section'        => $this->_section,
				'display_flg'    => $this->_display_flg,
			);
			// Redirect back to the settings page that was submitted.
			$goback = add_query_arg( $date, wp_get_referer() );
			wp_redirect( $goback );
			exit;
		}
	}


	public function update() {
		try {
			// リクエストパラメータのセットする
			$this->set_param();

			// エラーチェックを行う
			$err_lists = $this->check_validation();
			if($err_lists) throw new \Exception();

			global $wpdb;
			$wpdb->update(
				'schedules',
				array(
					'course_id'    => $this->_course_id,
					'week'         => $this->_week,
					'time'         => $this->_start_time,
					'section'      => $this->_section,
					'display_flg'  => $this->_display_flg,
				),
				// where句
				array( 'id' => $_POST['ID'] ),
			);
			add_settings_error( 'settings_errors', 'settings_errors', '更新が完了しました。', 'success' );
			set_transient( 'settings_errors', get_settings_errors(), 30 );
		} catch (\Exception $e) {
			$getMessage = $e->getMessage();

			// If no settings errors were registered add a general 'updated' message.
			foreach($err_lists as $err_list){
				add_settings_error( 'settings_errors', 'settings_errors', $err_list, 'error' );
			}

			// add_settings_error()した内容を、DBに一時保存する
			set_transient( 'settings_errors', get_settings_errors(), 30 );
		} finally{
			// Redirect back to the settings page that was submitted.
			$goback = add_query_arg( 'settings-updated', 'true', wp_get_referer() );
			wp_redirect( $goback );
			exit;

		}
	}


	public function set_param(){
		$this->_course_id       = $_POST['course_id'] ;
		$this->_week            = $_POST['week'] ;
		$this->_start_time      = $_POST['start_time'] ;
		$this->_section         = $_POST['section'] ;
		$this->_display_flg     = $_POST['display_flg'] ;
	}


	public function check_validation(){
		$err_lists = [];
		if(!$this->_course_id) array_push($err_lists,'コースが選択されていません');
		if($this->_week == "") array_push($err_lists,'曜日が入力されていません');
		if(!$this->_start_time) array_push($err_lists,'開始時間が入力されていません');
		if(!$this->_section) array_push($err_lists,'実施時間が入力されていません');
		if($this->_display_flg == "") array_push($err_lists,'スケジュールへの表示が入力されていません');

		// array_push($err_lists,'強制終了');
		return $err_lists ;
	}


	public function delete() {
		try {
			$ID = $_POST['ID'];

			// $timestamp = date('Y-m-d h:i:s');
			// $user = wp_get_current_user();
			// $userID = $user->ID;

			global $wpdb;
			$wpdb->update(
				'schedules',
				array(
						// 'updated_at' => $timestamp,
						'del_flg' => '1'
				),
				array( 'id' =>  $ID )   // where句
			);

			add_settings_error( 'settings_errors', 'settings_errors', '削除が完了しました。', 'success' );
			// add_settings_error()した内容を、DBに一時保存する
			set_transient( 'settings_errors', get_settings_errors(), 30 );
		} catch (\Exception $e) {
			add_settings_error( 'settings_errors', 'settings_errors', '異常が発生しました。', 'error' );
			// add_settings_error()した内容を、DBに一時保存する
			set_transient( 'settings_errors', get_settings_errors(), 30 );
		} finally{
			$goback = add_query_arg( 'settings-updated', 'true', './admin.php?page=list_of_schedule' );
			wp_redirect( $goback);
			exit;
		}
	}

}

$schedules = new Schedules();
if ($_SERVER['REQUEST_METHOD'] === 'POST') { //POSTが渡されたら
	if($_POST['update'] )$schedules -> update();
	if($_POST['register'] )$schedules -> register();
	if($_POST['delete'] )$schedules -> delete();
}
