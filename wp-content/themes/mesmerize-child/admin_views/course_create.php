<?php
    $membership_types = get_membership_type_list();
?>


<div class="wrap">

    <!-- 一覧画面 -->
    <h1 class="wp-heading-inline">コース登録</h1>

    <?php settings_errors(); ?>
    <!-- <table class="widefat fixed striped"> -->

    <form name="createuser" action="controller_course.php" method="post" >
        <?php settings_fields( 'company_settings' ); ?>
        <?php do_settings_sections( 'company_settings' ); ?>

        <table class="form-table" role="presentation" >
            <tbody>
                <tr class="form-field form-required">
                    <th scope="row"><label for="course_name">コース名 <span class="description" >(必須)</span></label></th>
                    <td><input name="course_name" type="text" value="<?= $_GET['course_name'] ?>" id="course_name" style="width:25em;" ></td>
                </tr>
                <tr class="form-field form-required">
                    <th scope="row"><label for="target">対象<span class="description" >(必須)</span></label></th>
                    <td><input name="target" type="text" value="<?= $_GET['target'] ?>" id="target" style="width:25em;" ></td>
                </tr>
                <tr class="form-field form-required">
                    <th scope="row"><label for="course_text">詳細<span class="description" >(必須)</span></label></th>
                    <td>
                        <textarea name="course_text" id="course_text" style="width:25em;height:8em;" ><?= $_GET['course_text'] ?></textarea>
                    </td>
                </tr>
                <tr class="form-field form-required">
                    <th scope="row"><label for="youtube_url	">youtube URL</label></th>
                    <td><input name="youtube_url" type="text" value="<?= $course->youtube_url ?>" id="youtube_url	" style="width:25em;" ></td>
                </tr>
                <tr class="form-field form-required">
                    <th scope="row"><label for="display_flg">HPへの表示</label></th>
                    <td>
                        <label><input type="radio" name="display_flg" value="1" >表示</label>
                        <label><input type="radio" name="display_flg" value="0" >非表示</label>
                    </td>
                </tr>
                <tr class="form-field form-required">
                    <th scope="row"><label for="">受講可能な会員種別</label></th>
                    <td>
                        <?php
                            foreach($membership_types as $membership_type){
                                ?>
                                <label><input type="checkbox" id="" name="membership_type_mapping[]" value="<?= $membership_type->id ?>" <?php if($membership_type->MT_id) echo " checked='checked'"; ?>  ><?= $membership_type->member_type_name ?></label>
                                <?php
                            }
                        ?>
                    </td>
                </tr>
                <tr class="form-field form-required">
                    <th scope="row"><label for="background_color">背景色</label></th>
                    <td><input name="background_color" type="color" value="#<?= $_GET['background_color'] ?>" id="background_color" style="width:15em;" ></td>
                </tr>
                <tr class="form-field form-required">
                    <th scope="row"><label for="border">枠線</label></th>
                    <td><input name="border" type="color" value="#<?= $_GET['border'] ?>" id="border" style="width:15em;" ></td>
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