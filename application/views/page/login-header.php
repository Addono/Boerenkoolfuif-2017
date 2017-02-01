<?php
/**
 * @author Adriaan Knapen <a.d.knapen@protonmail.com>
 * @date 29-1-2017
 */
?>
<div class="container">
    <div class="row">
        <div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3">
            <div class="card card-signup">
                <?=form_open()?>
                    <div class="header header-primary text-center">
                        <h4>Log in</h4>
                        <?=validation_errors()?>
                        <?php
                        foreach($errors as $error) {
                            echo '<p>'.$error.'</p>';
                        }
                        ?>
                    </div>
                    <p class="text-divider"></p>
                    <div class="content">

                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="material-icons">face</i>
                            </span>
                            <input type="text" name="username" class="form-control" placeholder="Gebruikersnaam..." value="<?=set_value('username')?>" />
                        </div>

                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="material-icons">lock_outline</i>
                            </span>
                            <input type="number" name="password" min="0" max="999999" placeholder="Pin..." class="form-control" />
                        </div>
                    </div>
                    <div class="footer text-center">
                        <input type="submit" value="Log in" class="btn btn-simple btn-primary btn-lg">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>