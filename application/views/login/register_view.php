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

                <?php echo form_open("register/index"); ?>
                <div class="field">
                    <div class="control has-icons-left">
                        <input class="input is-rounded" name="email" placeholder="Email" value="<?php echo set_value('email'); ?>" type="email" autocomplete="off">
                        <span class="icon is-small is-left">
                            <i class="fa fa-envelope"></i>
                        </span>
                    </div>
                </div>
                <?php echo form_error('email');?>

                <div class="field">
                    <div class="control has-icons-left">
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
                        <button type="submit" class="button is-vcentered is-rounded is-info" style="width: 200px;">Register</button>
                    </div>
                    <div class="column">
                        <a href="<?php echo base_url("login"); ?>" class="button is-vcentered is-rounded" style="border:none">Sign in</a>
                    </div>
                </div>
                <br>
                <center><div class="g-recaptcha" data-sitekey="<?php echo $this->config->item('recaptcha_sitekey'); ?>"></div></center>

                <br>
                <h6 class="subtitle has-text-grey-light is-6">Or register using...</h6>
                <div class="columns">
                    <div class="column">
                        <a href="<?php echo $google_login_url; ?>">
                            <div class="box has-background-white">
                                <article class="media">
                                    <div class="media-content">
                                        <div class="content has-text-centered">
                                            <i class="fab fa-google has-text-black"></i>
                                        </div>
                                    </div>
                                </article>
                            </div>
                        </a>
                    </div>
                    <div class="column">
                        <a href="<?php echo $google_login_url; ?>">
                            <div class="box has-background-white">
                                <article class="media">
                                    <div class="media-content">
                                        <div class="content has-text-centered">
                                            <i class="fab fa-facebook-f has-text-black"></i>
                                        </div>
                                    </div>
                                </article>
                            </div>
                        </a>
                    </div>
                    <div class="column">
                        <a href="<?php echo $google_login_url; ?>">
                            <div class="box has-background-white">
                                <article class="media">
                                    <div class="media-content">
                                        <div class="content has-text-centered">
                                            <i class="fab fa-twitter has-text-black"></i>
                                        </div>
                                    </div>
                                </article>
                            </div>
                        </a>
                    </div>
                </div>

                <?php echo form_close(); ?>

            </section>
        </div>
        <div id="logininclined" class="hero is-fullheight column is-5">
        </div>
    </div>
</body>
