<?php
    if(!$_GET['ID']){
        echo "<h2 class='wp-heading-inline'>先生一覧から編集する先生を選択してください</h2>";
        exit;
    }else{
        $instructor = get_instructor($_GET['ID']);
        $instructor_awards = get_instructor_awards($_GET['ID'], false);
        if(!$instructor){
            echo "不正なアクセスを検知しました。";
            exit;
        }
    }
?>

<div class="wrap">
    <h1 class="wp-heading-inline">インストラクター編集</h1>
    <?php settings_errors(); ?>
    <?php if($_GET['award'] == true){ ?>
        <br>
        <form name="" action="controller_instructor.php" method="post" >
            <input type="hidden" name="id" value="<?= $_GET['ID'] ?>" >
            <input type="hidden" name="award_insert" value="award_insert" >
            <input type="submit" name="" value="新しいアワードを追加する" >
        </form>
        <table class="form-table" role="presentation" >
            <tbody>
                <tr">
                    <th>順番</th>
                    <th>アワード名</th>
                    <th>HPへの表示</th>
                    <th>更新ボタン</th>
                    <th>削除ボタン</th>
                </tr>
                <?php foreach($instructor_awards as $data){ ?>
                    <tr class="form-field form-required">
                        <form name="" action="controller_instructor.php" method="post" >
                            <td><input name="rank" type="number" value="<?= $data->rank ?>" style="width:5em;" ></td>
                            <td><input name="award" type="text" value="<?= $data->award ?>" style="width:50em;" ></td>
                            <td>
                                <label><input type="radio" name="display_flg" value="1" <?php if($data->display_flg == 1) echo " checked='checked'"; ?> >表示</label><br>
                                <label><input type="radio" name="display_flg" value="0" <?php if($data->display_flg == 0) echo " checked='checked'"; ?> >非表示</label>
                            </td>
                            <td>
                                <input type="hidden" name="ID" value="<?= $data->id ?>" >
                                <input type="hidden" name="award_update" value="award_update" >
                                <input type="submit" name="" value="更新する" >
                            </td>
                        </form>
                        <form name="" action="controller_instructor.php" method="post" >
                            <td>
                                <input type="hidden" name="ID" value="<?= $data->id ?>" >
                                <input type="hidden" name="award_delete" value="award_delete" >
                                <input type="submit" name="" value="削除" >
                            </td>
                        </form>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

    <?php }else{ ?>
        <form name="createuser" action="controller_instructor.php" method="post" >
            <?php settings_fields( 'company_settings' ); ?>
            <?php do_settings_sections( 'company_settings' ); ?>

            <table class="form-table" role="presentation" >
                <tbody>
                    <tr class="form-field form-required">
                        <th scope="row"><label for="instructor_name">インストラクター名 <span class="description" >(必須)</span></label></th>
                        <td><input name="instructor_name" type="text" value="<?= $instructor->instructor_name ?>" id="instructor_name" style="width:25em;" ></td>
                    </tr>
                    <tr class="form-field form-required">
                        <th scope="row"><label for="instructor_level">タイトル<span class="description" >(必須)</span></label></th>
                        <td><input name="instructor_level" type="text" value="<?= $instructor->instructor_level ?>" id="instructor_level" style="width:25em;" ></td>
                    </tr>
                    <tr class="form-field form-required">
                        <th scope="row"><label for="band_colour">帯色<span class="description" >(必須)</span></label></th>
                        <td><input name="band_colour" type="text" value="<?= $instructor->band_colour ?>" id="band_colour" style="width:25em;" ></td>
                    </tr>
                    <tr class="form-field form-required">
                        <th scope="row"><label for="email">メールアドレス <span class="description">(必須)</span></label></th>
                        <td><input name="email" type="email" value="<?= $instructor->email ?>" id="email" style="width:25em;" ></td>
                    </tr>
                    <tr class="form-field form-required">
                        <th scope="row"><label for="award">アワード</label></th>
                        <td>
                            <ul>
                                <?php
                                    foreach($instructor_awards as $data){
                                        // HPへ表示するアワードは文字を太くする
                                        if($data->display_flg==1){
                                            $fontStyle = 'font-weight:800;';
                                        }else{
                                            $fontStyle = 'font-weight:400;';
                                        }
                                        echo '<li style="list-style: inside; '. $fontStyle .'">' . $data->award . '</li>';
                                    }
                                ?>
                            </ul>
                            <a href="admin.php?page=edit_teacher_page&ID=<?= $instructor->id ?>&award=true" >編集する</a>
                        </td>
                    </tr>
                    <tr class="form-field form-required">
                        <th scope="row"><label for="faceBook">SNS</label></th>
                        <td>
                                FB      　<input name="faceBook_url" type="url" value="<?= $instructor->faceBook_url ?>" id="faceBook" style="width:25em;" ><br>
                                IG      　<input name="instagram_url" type="url" value="<?= $instructor->instagram_url ?>" id="instagram" style="width:25em;" ><br>
                                TW      　<input name="twitter_url" type="url" value="<?= $instructor->twitter_url ?>" id="twitter" style="width:25em;" >
                        </td>
                    </tr>
                    <tr class="form-field form-required">
                        <th scope="row"><label for="introduction">自己紹介文</label></th>
                        <td>
                            <textarea name="introduction" style="width:40em;min-height:200px;" id="introduction" ><?= $instructor->introduction ?></textarea>
                        </td>
                    </tr>
                    <tr class="form-field form-required">
                        <th scope="row"><label for="display_flg">HPへの表示</label></th>
                        <td>
                            <label><input type="radio" name="display_flg" value="1" <?php if($instructor->display_flg == 1) echo " checked='checked'"; ?> >表示</label>
                            <label><input type="radio" name="display_flg" value="0" <?php if($instructor->display_flg == 0) echo " checked='checked'"; ?> >非表示</label>
                        </td>
                    </tr>
                    <tr class="form-field form-required">
                        <th scope="row"><label for="display_flg">プライベートレッスン</label></th>
                        <td>
                            1回<input name="lesson_fee" type="lesson_fee" value="<?= $instructor->lesson_fee ?>" id="lesson_fee" style="width:10em;" >円<br>
                            4回<input name="lesson_fee_4time" type="lesson_fee_4time" value="<?= $instructor->lesson_fee_4time ?>" id="lesson_fee_4time" style="width:10em;" >円
                        </td>
                    </tr>

                </tbody>
            </table>
            <input type="hidden" name="ID" value="<?= $instructor->id ?>" >
            <input type="hidden" name="update" value="update" >
            <input type="submit" name="" value="更新する" >
        </form>

    <?php } ?>


    <!-- <table class="widefat fixed striped"> -->





</div>

