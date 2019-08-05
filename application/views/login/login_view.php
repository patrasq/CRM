<body style="background:#fff !important;">
    
    <link href="<?= base_url("assets/css/font-awesome.min.css"); ?>">
    <div class="columns is-vcentered" style="background:#fff">
        <div class="column hero is-fullheight is-7">
            <section style=" margin: auto;width: 50%;" class="section">
                <?php if($this->session->flashdata('error') != null): ?>
                <div class="notification is-danger">
                    <button class="delete"></button>
                    <?php echo $this->session->flashdata('error'); ?>
                </div>
                <?php endif; ?>
                <?php if($this->session->flashdata('success') != null): ?>
                <div class="notification is-success">
                    <button class="delete"></button>
                    <?php echo $this->session->flashdata('success'); ?>
                </div>
                <?php endif; ?>
                <h1 class="title titlelight">
                    Welcome to LeMonkey
                </h1>
                <br>

                <?php echo form_open("login/index"); ?>

                <div class="field">
                    <div class="control has-icons-right">
                        <input class="input is-rounded" name="email" placeholder="Email" value="<?php echo set_value('email'); ?>" type="email" autocomplete="off">
                        <span class="icon is-small is-left">
                            <i class="fa fa-envelope"></i>
                        </span>
                    </div>
                </div>
                <?php echo form_error('email');?>

                <div class="field">
                    <div class="control has-icons-right">
                        <input class="input  is-rounded" name="password" placeholder="Password" value="<?php echo set_value('password'); ?>" type="password" autocomplete="off">
                        <span class="icon is-small is-left">
                            <i class="fa fa-key"></i>
                        </span>
                    </div>
                </div>
                <?php echo form_error('password');?>


                <br>
                <div class="columns">
                    <div class="column">
                        <button type="submit" class="button is-vcentered is-rounded is-info" style="width: 200px;">Sign in</button>
                    </div>
                    <div class="column">
                        <button type="submit" class="button is-vcentered is-rounded" style="border:none">Lost password?</button>
                    </div>
                </div>
                <br>
                <!--<center><div class="g-recaptcha" data-sitekey="<?php echo $this->config->item('recaptcha_sitekey'); ?>"></div></center>-->

                <?php echo form_close(); ?>
                <br>

                <div class="has-text-centered">
                    <a href="<?php echo base_url("register"); ?>"> <?php echo $text_alternativebottom; ?></a>
                </div>
            </section>
        </div>
        <div id="logininclined" class="hero is-fullheight column is-5">
            <img src="<?= base_url("assets/images/backgrounds/illustration_login.svg"); ?>" style="margin-top:135px;">
        </div>
    </div>
</body>