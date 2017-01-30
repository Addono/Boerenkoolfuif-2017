<?php
/**
 * @author Adriaan Knapen <a.d.knapen@protonmail.com>
 * @date 29-1-2017
 */
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <?php if(isset($redirect)) { ?>
    <meta http-equiv="refresh" content="<?php echo $redirectTime?>; url=<?php echo $redirect?>">
    <script type="text/javascript">
        window.setTimeout(function() {
            window.location.href = "<?php echo $redirect?>"
        }, <?php echo $redirectTime * 1000 ?>);
    </script>
    <?php } ?>
    <link rel="apple-touch-icon" sizes="76x76" href="<?php echo base_url('resources/img/apple-icon.png')?>">
    <link rel="icon" type="image/png" href="<?php echo base_url('resources/img/favicon.png')?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

    <title>Boerenkoolfuif 2017</title>

    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />

    <!--     Fonts and icons     -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons" />
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" />

    <!-- CSS Files -->
    <link href="<?php echo base_url('resources/css/bootstrap.min.css')?>" rel="stylesheet" />
    <link href="<?php echo base_url('resources/css/material-kit.css')?>" rel="stylesheet"/>
    <link href="<?php echo base_url('resources/css/style.css')?>" rel="stylesheet" />
</head>

<body>

<nav class="navbar navbar-transparent navbar-absolute">
    <div class="container">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navigation">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="<?php echo site_url()?>">ProlPa StampAwards 2017</a>
        </div>

        <div class="collapse navbar-collapse" id="navigation">
            <ul class="nav navbar-nav navbar-right">
                <?php if($loggedIn) { ?>
                <li>
                    <a href="<?php echo site_url('account');?>" class="btn btn-simple btn-white" target="_self">
                        <i class="material-icons header-icon">account_box</i> Mijn account
                    </a>
                </li>
                <li>
                    <form class="form" method="post" action="<?php echo site_url('')?>" class="inline-form">
                        <input type="hidden" name="type" value="logout" />
                        <button type="submit" class="btn btn-simple btn-white">
                            <i class="material-icons header-icon">account_box</i> Uitloggen
                        </button>
                    </form>
                </li>
                <?php } else { ?>
                <li>
                    <a href="<?php echo site_url('login');?>" class="btn btn-simple btn-white" target="_self">
                        <i class="material-icons header-icon">account_box</i> Inloggen
                    </a>
                </li>
                <?php } ?>
                <li>
                    <a href="https://twitter.com/impeesa_afoort" target="_blank" class="btn btn-simple btn-white btn-just-icon">
                        <i class="fa fa-twitter"></i>
                    </a>
                </li>
                <li>
                    <a href="https://www.facebook.com/impeesa.amersfoort" target="_blank" class="btn btn-simple btn-white btn-just-icon">
                        <i class="fa fa-facebook-square"></i>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="wrapper">
    <div class="header header-filter first" style="background-image: url('https://images.unsplash.com/photo-1423655156442-ccc11daa4e99?crop=entropy&dpr=2&fit=crop&fm=jpg&h=750&ixjsv=2.1.0&ixlib=rb-0.3.5&q=50&w=1450');">
        <div class="container">
            <div class="row">
                <div class="col-md-6 header-text">
