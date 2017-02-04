<?php
/**
 * @author Adriaan Knapen <a.d.knapen@protonmail.com>
 * @date 4-2-2017
 */
?>
<div class="">
    <div class="contentsection-landing">
        <div class="row">

                <h1>Jouw recepten</h1>
                <?php if(count($receipts)===0) {?>
                    <h4>Je hebt nog geen recepten ingeleverd.</h4>
                <?php } else { ?>
                    <h4>Hier staan alle recepten die jij hebt ingeleverd.</h4>
                <?php foreach($countryFriendly as $country => $friendly) { ?>
                    <div class="col-sm-12 col-sm-offset-0">
                    <h2><?=$friendly?></h2>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Groente</th>
                                <th>Aardappelen</th>
                                <th>Vlees</th>
                                <th>Specialiteit</th>
                                <th>Score</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php foreach($receipts as $r) {
                                if($r->country !== $country) {continue;}?>
                                <tr>

                                    <td><?=$r->resource0?></td>
                                    <td><?=$r->resource1?></td>
                                    <td><?=$r->resource2?></td>
                                    <td><?=ucfirst($specialties[$r->specialty])?></td>
                                    <td><?=$r->score?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                    </div>
                    <?php } ?>
                <?php } ?>
        </div>
    </div>
</div>
