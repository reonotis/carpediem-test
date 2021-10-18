
<div class="wrap">

    <?php if($_GET['ID']) : ?>
        <!-- 編集画面 -->
        <h1 class="wp-heading-inline">先生編集画面</h1>



    <?php else : ?>
        <!-- 一覧画面 -->
        <h1 class="wp-heading-inline">先生一覧</h1>
        <a href="./admin.php?page=register_teacher_page" class="page-title-action">新規追加</a>

        <?php settings_errors(); ?>
        <!-- <table class="widefat fixed striped"> -->
        <table class="widefat fixed striped">
            <thead>
                <tr>
                    <th>名前</th>
                    <th>キャリア</th>
                    <th>コメント</th>
                    <th>HP表示</th>
                    <th>編集</th>
                    <th>削除</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if($instructors){
                    foreach($instructors as $instructor){
                        ?>
                            <tr>
                                <th><?= $instructor->instructor_name ?></th>
                                <th><?= $instructor->instructor_level ?></th>
                                <th><?= $instructor->band_colour ?></th>
                                <th>
                                    <?php if( $instructor->display_flg == 1){
                                        echo "表示" ;
                                    }else{
                                        echo "非表示" ;
                                    }
                                    ?>
                                </th>
                                <th><a href="./admin.php?page=edit_teacher_page&ID=<?= $instructor->id ?>" >編集</a></th>
                                <th>
                                    <form method="post" name="delete_form<?= $instructor->id ?>" action="controller_instructor.php">
                                        <input type="hidden" name="delete" value="delete" >
                                        <input type="hidden" name="id" value="<?= $instructor->id ?>" >
                                        <a href="javascript:delete_form<?= $instructor->id ?>.submit()" onclick="return deleteConfirm();" >削除</a>
                                    </form>
                                </th>
                            </tr>
                        <?php
                    }

                }else{
                    echo "<tr><td colspan='7'>公表されている開催予定はありません</td></tr>";
                }
                ?>
            </tbody>
        </table>

    <?php endif ; ?>
</div>





<script type="text/javascript">
    function deleteConfirm(){
        var value = confirm('削除しますか');
        return value;
    }

</script>

