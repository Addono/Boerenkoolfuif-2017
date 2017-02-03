<?php
/**
 * @author Adriaan Knapen <a.d.knapen@protonmail.com>
 * @date 1-2-2017
 */
?>
<?php if($success) { ?>
<div class="container vmargin">
    <div class="col-md-6 col-md-offset-3">
        <div class="section main-raised card text-center">
            <h1>Cijfer: <?=$score?></h1>
            <p>Voor <?=set_value('username')?></p>
        </div>
    </div>
</div>
<?php } ?>
<div class="col-md-6 vmargin">
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2 col-sm-10 col-sm-offset-1">
                <div class="card card-signup">
                    <?=form_open()?>
                    <div class="header header-primary text-center">
                        <h4><?=$country?> recept toevoegen</h4>
                        <?=validation_errors()?>
                        <?php
                        foreach($errors as $error) {
                            echo '<p>'.$error.'</p>';
                        }
                        ?>
                    </div>
                    <p class="text-divider"></p>
                    <div class="content">

                        <div class="input-group horizontal-align-radio">
                            <span class="input-group-addon">
                                <i class="material-icons">group</i>
                            </span>
                            <?php foreach($usernames as $u) { ?>
                            <div class="radio">
                                    <label>
                                        <input type="radio" name="username" value="<?=$u->username?>" <?=!$success&&set_value('username')===$u->username?'checked ':false?>/>
                                        <?=$u->username?>
                                    </label>
                            </div>
                            <?php } ?>
                        </div>

                        <?php
                        $count = 0;
                        foreach($resources as $r) {
                            $name = 'resource'.$count;
                            ?>
                        <h3><?=ucfirst($r)?></h3>
                        <div class="input-group horizontal-align-radio">
                            <span class="input-group-addon">
                                <i class="material-icons">local_dining</i>
                            </span>
    <?php                       for($i = 1; $i <= 6; $i++) { ?>
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="<?=$name?>" value="<?=$i?>" <?=!$success&&set_value($name)===strval($i)?'checked ':false?>/>
                                        <?=$i?>
                                    </label>
                                </div>
    <?php                       } ?>
                        </div>
                        <?php
                            $count++;
                        }
                        ?>
                        <h3>Specialiteit</h3>
                        <div class="input-group horizontal-align-radio">
                            <span class="input-group-addon">
                                <i class="material-icons">star</i>
                            </span>
                            <table>
                            <?php
                            $count = 0;
                            foreach($specialties as $key => $name) {
                                if($count % 2 == 0) {
                                    echo "<tr>";
                                }
                                ?>
                                <td>
                                    <div class="radio">
                                        <label>
                                            <input type="radio" name="specialty" value="<?=$key?>"  <?=!$success&&set_value('specialty')===$key?'checked ':false?>/>
                                            <?=ucfirst($name)?>
                                        </label>
                                    </div>
                                </td>
                            <?php
                                if($count % 2 != 0) {
                                    echo "</tr>";
                                }
                                $count++;
                            } ?>
                            </table>
                        </div>

                        <div class="footer text-center">
                            <input type="submit" value="Toevoegen" class="btn btn-simple btn-primary btn-lg">
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
</div>
