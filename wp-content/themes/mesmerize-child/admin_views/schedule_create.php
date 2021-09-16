<?php
    $courses = get_courses();
?>
<div class="wrap">

    <!-- 一覧画面 -->
    <h1 class="wp-heading-inline">スケジュール登録</h1>

    <?php settings_errors(); ?>
    <!-- <table class="widefat fixed striped"> -->

    <form name="" action="controller_schedules.php" method="post" >
        <?php settings_fields( 'company_settings' ); ?>
        <?php do_settings_sections( 'company_settings' ); ?>

        <table class="form-table" role="presentation" >
            <tbody>
                <tr class="form-field form-required">
                    <th scope="row"><label for="course_id">実施コース <span class="description" >(必須)</span></label></th>
                    <td>
                        <select name="course_id"  id="course_id" style="width:25em;">
                            <option value="" >選択してください</option>
                                <?php foreach($courses as $course){ ?>
                                    <option value="<?= $course->id ?>" <?php if( $_GET['course_id']  == $course->id )echo " selected"; ?>  ><?= $course->course_name ?></option>
                                <?php } ?>
                        </select>
                    </td>
                </tr>
                <tr class="form-field form-required">
                    <th scope="row"><label for="week">曜日<span class="description" >(必須)</span></label></th>
                    <td>
                        <label><input type="radio" name="week" value="0" checked='checked'>日</label>
                        <label><input type="radio" name="week" value="1" <?php if( $_GET['week']  == 1 )echo " checked='checked'"; ?> >月</label>
                        <label><input type="radio" name="week" value="2" <?php if( $_GET['week']  == 2 )echo " checked='checked'"; ?> >火</label>
                        <label><input type="radio" name="week" value="3" <?php if( $_GET['week']  == 3 )echo " checked='checked'"; ?> >水</label>
                        <label><input type="radio" name="week" value="4" <?php if( $_GET['week']  == 4 )echo " checked='checked'"; ?> >木</label>
                        <label><input type="radio" name="week" value="5" <?php if( $_GET['week']  == 5 )echo " checked='checked'"; ?> >金</label>
                        <label><input type="radio" name="week" value="6" <?php if( $_GET['week']  == 6 )echo " checked='checked'"; ?> >土</label>
                    </td>
                </tr>
                <tr class="form-field form-required">
                    <th scope="row"><label for="start_time">開始時間<span class="description" >(必須)</span></label></th>
                    <td><input name="start_time" type="time" value="<?= $_GET['start_time'] ?>" id="start_time" style="width:25em;" step="900"></td>
                </tr>
                <tr class="form-field form-required">
                    <th scope="row"><label for="section">実施時間<span class="description" >(必須)</span></label></th>
                    <td>
                        <select name="section"  id="section" style="width:25em;">
                            <option value=""　>選択してください</option>
                            <option value="1" <?php if( $_GET['section'] == 1 )echo " selected"; ?> >15分</option>
                            <option value="2" <?php if( $_GET['section'] == 2 )echo " selected"; ?> >30分</option>
                            <option value="3" <?php if( $_GET['section'] == 3 )echo " selected"; ?> >45分</option>
                            <option value="4" <?php if( $_GET['section'] == 4 )echo " selected"; ?> >60分</option>
                            <option value="5" <?php if( $_GET['section'] == 5 )echo " selected"; ?> >75分</option>
                            <option value="6" <?php if( $_GET['section'] == 6 )echo " selected"; ?> >90分</option>
                            <option value="7" <?php if( $_GET['section'] == 7 )echo " selected"; ?> >105分</option>
                            <option value="8" <?php if( $_GET['section'] == 8 )echo " selected"; ?> >120分</option>
                        </select>
                    </td>
                </tr>
                <tr class="form-field form-required">
                    <th scope="row"><label for="display_flg">スケジュール表への表示</label></th>
                    <td>
                        <label><input type="radio" name="display_flg" value="1" <?php if( $_GET['display_flg']  == 1 )echo " checked='checked'"; ?> >表示</label>
                        <label><input type="radio" name="display_flg" value="0" <?php if( $_GET['display_flg']  == 0 )echo " checked='checked'"; ?> >非表示</label>
                    </td>
                </tr>
            </tbody>
        </table>
        <input type="hidden" name="register" value="register" >
        <input type="submit" name="" value="送信" >
    </form>
</div>


<script type="text/javascript">


</script>
<?php


?>