<?php
/**
 * @author Adriaan Knapen <a.d.knapen@protonmail.com>
 * @date 1-2-2017
 */
?>
<div class="container">
    <div class="row">
        <div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3">
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
                                    <input type="radio" name="username" value="<?=$u->username?>">
                                    <?=$u->username?>
                                </label>
                        </div>
                        <?php } ?>
                    </div>

                    <?php
                    $count = 0;
                    foreach($resources as $r) { ?>
                    <h3><?=$r?></h3>
                    <div class="input-group horizontal-align-radio">
                        <span class="input-group-addon">
                            <i class="material-icons">local_dining</i>
                        </span>
<?php                       for($i = 0; $i < 6; $i++) { ?>
                            <div class="radio">
                                <label>
                                    <input type="radio" name="resource<?=$count?>" value="<?=$i?>">
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
                            <i class="material-icons">local_dining</i>
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
                                        <input type="radio" name="specialty" value="<?=$key?>">
                                        <?=$name?>
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
