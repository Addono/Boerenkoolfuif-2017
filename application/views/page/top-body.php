<?php
/**
 * @author Adriaan Knapen <a.d.knapen@protonmail.com>
 * @date 3-2-2017
 */
?>
<div class="section-landing">
    <div class="content">
        <h1>Top scores</h1>
        <div class="row">
            <?php
            $countries = ['netherlands' => 'Nederland', 'belgium' => 'België', 'france' => 'Frankrijk', 'germany' => 'Duitsland'];
            foreach($countries as $key => $country) {
            ?>
            <div class="col-md-2 col-md-offset-1">
                <h2><?=$country?></h2>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Groeps naam</th>
                            <th>Score</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $count = 0;
                    foreach($top[$key] as $entry) {
                        $count++?>
                        <tr>
                            <td><?=$count?></td>
                            <td><?=$entry->username?></td>
                            <td><?=$entry->score?></td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
            <?php } ?>
        </div>
    </div>
</div>
