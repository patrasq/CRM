<?php $this->load->view('dashboard/menu'); ?>
<link href="<?php echo base_url("assets/css/font-awesome.min.css"); ?>">

<div class="column" style="padding:30px;">
    <div class="columns">
        <div class="column">
            <a href="<?php echo base_url("dashboard/settings/account"); ?>">
                <div class="settingsblock box has-background-info">
                    <article class="media">
                        <div class="media-content">
                            <div class="content has-text-centered">
                                <i class="fas fa-user has-text-white is-size-1" style="margin-bottom: 30px;"></i>
                                <br>
                                <h5 class="subtitle is-5 has-text-white has-text-weight-light">Account</h5>
                            </div>
                        </div>
                    </article>
                </div>
            </a>
        </div>
        <div class="column">
            <a href="<?php echo base_url("dashboard/settings/business/"); ?>">
                <div class="settingsblock box has-background-info">
                    <article class="media">
                        <div class="media-content">
                            <div class="content has-text-centered">
                                <i class="far fa-building has-text-white is-size-1" style="margin-bottom: 30px;"></i>
                                <br>
                                <h5 class="subtitle is-5 has-text-white has-text-weight-light"><?= $my_business_name; ?></h5>
                            </div>
                        </div>
                    </article>
                </div>
            </a>
        </div>
    </div>
</div>
<?php $this->load->view('dashboard/footer'); ?>