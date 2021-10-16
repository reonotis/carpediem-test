
<div class="wrap">

        <!-- 一覧画面 -->
        <h1 class="wp-heading-inline">スケジュール一覧</h1>
        <a href="./admin.php?page=register_schedule_page" class="page-title-action">新規追加</a>

        <?php settings_errors(); ?>
        <!-- <table class="widefat fixed striped"> -->
        <table class="widefat striped">
            <thead>
                <tr>
                    <th>開催曜日</th>
                    <th>実施時間</th>
                    <th>コース名</th>
                    <th>HP表示</th>
                    <th>編集</th>
                    <th>削除</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if($schedules){
                    foreach($schedules as $schedule){
                        ?>
                            <tr>
                                <td><?= $schedule->week_ja ?>曜日</td>
                                <td><?= $schedule->implementationDate ?> までの <?= $schedule->period ?>分間
                                <td>
                                    <div class="" style="background:#<?= $schedule->background ?>;border:solid 1px #<?= $schedule->border ?>;padding:5px;">
                                        <?= $schedule->course_name ?>
                                    </div>
                                </td>
                                <td><?= $schedule->display_name ?></td>
                                <td><a href="./admin.php?page=edit_schedule_page&ID=<?= $schedule->id ?>" >編集</a></td>
                                <td>
                                    <form action="controller_schedules.php" method="post" >
                                        <input type="hidden" name="ID" value="<?= $schedule->id ?>" >
                                        <input type="submit" name="delete" value="削除" onclick="return confirm_deleteSchedule();" >
                                    </form>
                                </td>
                            </tr>
                        <?php
                    }

                }else{
                    echo "<tr><td colspan='7'>登録されているスケジュールはありません</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <h1 class="wp-heading-inline">スケジュール表</h1>
        <!-- <?php echo make_scheduleTable(); ?> -->
</div>





<script type="text/javascript">
    function confirm_deleteSchedule(){
        var result = window.confirm('このスケジュールを削除しますか？');
        if( result ) return true; return false;
    }
</script>

