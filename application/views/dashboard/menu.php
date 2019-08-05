<?php

//not a best practice, but a 'in a delayed train' workaround
$text_projects              = $this->lang->line('dashboard_projects');
$text_add_project           = $this->lang->line('dashboard_add_project');
$text_settings              = $this->lang->line('dashboard_settings');
$text_signout               = $this->lang->line('dashboard_signout');
$text_logins                = $this->lang->line('dashboard_logins');
$text_news                  = $this->lang->line('dashboard_news');

$text_hr                    = 'Human resources management';
$text_projects              = 'Projects management';
$text_insights              = 'Insights';
$text_finance               = '';

/* MENU OF - HRM */
$text_employees             = $this->lang->line('dashboard_employees');
$text_departments           = $this->lang->line('dashboard_departments');
/* */

/* MENU OF - Projects */
$text_project_overview      = "Overview";
/* */

/* MENU OF - CEO */
$text_teds                  = "TEDs";
$text_agenda                = "Agenda";
/* */

$text_home                  = "Home";

$text_finance_overview      = "Overview";
$text_finance_accounting    = "Accounting";
?>

<link href="https://fonts.googleapis.com/css?family=Yantramanav:300" rel="stylesheet">

<section class="hero is-medium is-info is-bold" style="height:200px">
    <figure class="image is-256x256" style="top:20px;margin:auto" data-aos="fade-down" data-aos-delay="200" data-aos-offset="200" data-aos-easing="ease-out-quart" class="aos-init aos-animate">
        <a href="<?= base_url("dashboard"); ?>"><img src="<?= base_url("assets/images/logo_ba.png"); ?>"></a>
    </figure>

    <div class="hero-head">
        <nav class="navbar">
            <div class="container">
                <div id="navbarMenu" class="navbar-menu is-active">
                    <div class="navbar-end has-text-centered">
                        <span class="navbar-item">
                            <?php if($this->session->userdata('logged_in') !== null) { ?>
                            <a class="button is-white is-outlined is-small" href="<?= base_url("dashboard/logout"); ?>">
                                <span class="icon">
                                    <i class="fa fa-user"></i>
                                </span>
                                <span>Sign out</span>
                            </a>
                            <?php } ?>
                        </span>
                    </div>
                </div>
            </div>
        </nav>
    </div>
</section>
<?php if($this->session->flashdata('error') != null): ?>
<div class="notification is-danger">
    <button class="delete"></button>
    <?= $this->session->flashdata('error'); ?>
</div>
<?php endif; ?>
<?php if($this->session->flashdata('success') != null): ?>
<div class="notification is-success">
    <button class="delete"></button>
    <?= $this->session->flashdata('success'); ?>
</div>
<?php endif; ?>
<section>
    <div class="columns">
        <div class="column is-one-fifth" style="padding:30px;">
            <aside class="menu" data-aos="fade-right" data-aos-delay="200" data-aos-offset="200" data-aos-easing="ease-out-quart" class="aos-init aos-animate">
                <p class="menu-label">
                    General
                </p>
                <ul class="menu-list">
                    <li><a href="<?= base_url("dashboard"); ?>" <?= is_menu_active("dashboard", "shouldbeerror"); ?>><?= $text_home; ?></a></li>
                    <li><a href="<?= base_url("dashboard/settings"); ?>" <?= is_menu_active("settings"); ?>><?= $text_settings; ?></a></li>
                </ul>
                <p class="menu-label">
                    <?= $text_hr; ?>
                </p>
                <ul class="menu-list">
                    <li>
                        <ul>
                            <li><a <?= is_menu_active("departments"); ?> href="<?= base_url("hr/departments"); ?>"><?= $text_departments; ?></a></li>
                            <li><a <?= is_menu_active("employees", "employee"); ?> href="<?= base_url("hr/employees"); ?>"><?= $text_employees; ?></a></li>
                        </ul>
                    </li>
                </ul>

                <p class="menu-label">
                    <?= $text_projects; ?>
                </p>
                <ul class="menu-list">
                    <li>
                        <ul>
                            <li><a <?= is_menu_active("instagram"); ?> href="<?= base_url("projects/"); ?>"><?= $text_project_overview; ?></a></li>
                        </ul>
                    </li>
                </ul>

                <p class="menu-label">
                    <?= $text_insights; ?>
                </p>
                <ul class="menu-list">
                    <li>
                        <ul>
                            <li><a <?= is_menu_active("teds"); ?> href="<?= base_url("ceo/teds"); ?>"><?= $text_teds; ?></a></li>
                            <li><a <?= is_menu_active("agenda"); ?> href="<?= base_url("ceo/agenda"); ?>"><?= $text_agenda; ?></a></li>
                        </ul>
                    </li>
                </ul>
            </aside>
        </div>