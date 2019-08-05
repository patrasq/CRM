<body >
    <!-- Pageloader -->
    <!-- <div class="pageloader is-theme"></div>
<div class="infraloader"></div> -->
    <section class="hero is-info is-fullheight is-bold" id="mainbackground">
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
        <div class="hero-head">
            <nav class="navbar">
                <div class="container" style="margin-left:128px;">
                    <div class="navbar-brand" data-aos="fade-down" data-aos-delay="200" data-aos-offset="200" data-aos-easing="ease-out-quart" class="aos-init aos-animate">
                        <a id="navbarname" class="navbar-item" href="../">
                            <img src="<?php echo base_url("assets/images/logo_gradient.png"); ?>" alt="Logo">
                        </a>
                        <span class="navbar-burger burger" data-target="navbarMenu">
                            <span></span>
                            <span></span>
                            <span></span>
                        </span>
                    </div>

                </div>
            </nav>
        </div>
        <div class="hero-body">
            <div class="container" style="margin-left: 99px;">
                <h1 class="title is-1" style="font-size:4rem;" data-aos="fade-right" data-aos-delay="200" data-aos-offset="200" data-aos-easing="ease-out-quart" class="aos-init aos-animate">
                    <?php echo $title; ?>
                </h1>
                <h2 class="subtitle is-4" style="color: #007eff;letter-spacing: 12px;font-weight: bold;font-family: 'Montserrat';" data-aos="fade-right" data-aos-delay="400" data-aos-offset="200" data-aos-easing="ease-out-quart" class="aos-init aos-animate">
                    <?php echo $subtitle; ?>
                </h2>
                <br>
                <a class="button is-info is-rounded is-large gradient-rounded-button" data-aos="fade-up" data-aos-delay="600" data-aos-offset="200" data-aos-easing="ease-out-quart" class="aos-init aos-animate">LEARN HOW</a>
            </div>
        </div>
    </section>
    <div class="breakline"></div>
    <section class="">
        <div class="columns has-text-centered">
            <div class="feature column" data-aos="fade-up" data-aos-delay="100" data-aos-offset="200" data-aos-easing="ease-out-quart" class="aos-init aos-animate">
                <figure class="image is-128x128 mainpagefigure" data-aos="fade-up" data-aos-delay="100" data-aos-offset="200" data-aos-easing="ease-out-quart" class="aos-init aos-animate">
                    <img src="assets/images/icons/hr.png" alt="" data-aos="fade-up" data-aos-delay="100" data-aos-offset="200" data-aos-easing="ease-out-quart" class="aos-init aos-animate">
                </figure>
                <br>
                <h4 class="title is-6"><?php echo $human_resources; ?></h4>
                <p><?php echo $human_resources_text; ?></p>
            </div>
            <div class="feature column" data-aos="fade-up" data-aos-delay="100" data-aos-offset="200" data-aos-easing="ease-out-quart" class="aos-init aos-animate">
                <figure class="image is-128x128 mainpagefigure" data-aos="fade-up" data-aos-delay="100" data-aos-offset="200" data-aos-easing="ease-out-quart" class="aos-init aos-animate">
                    <img src="assets/images/icons/FINANCE.png" alt="" data-aos="fade-up" data-aos-delay="100" data-aos-offset="200" data-aos-easing="ease-out-quart" class="aos-init aos-animate">
                </figure>
                <br>
                <h4 class="title is-6"><?php echo $finance; ?></h4>
                <p><?php echo $finance_text; ?></p>
            </div>
            <div class="feature column" data-aos="fade-up" data-aos-delay="100" data-aos-offset="200" data-aos-easing="ease-out-quart" class="aos-init aos-animate">
                <figure class="image is-128x128 mainpagefigure" data-aos="fade-up" data-aos-delay="100" data-aos-offset="200" data-aos-easing="ease-out-quart" class="aos-init aos-animate">
                    <img src="assets/images/icons/pr.png" alt="" data-aos="fade-up" data-aos-delay="100" data-aos-offset="200" data-aos-easing="ease-out-quart" class="aos-init aos-animate">
                </figure>
                <br>
                <h4 class="title is-6"><?php echo $public_relations; ?></h4>
                <p><?php echo $public_relations_text; ?></p>
            </div>
            <div class="feature column" data-aos="fade-up" data-aos-delay="100" data-aos-offset="200" data-aos-easing="ease-out-quart" class="aos-init aos-animate">
                <figure class="image is-128x128 mainpagefigure" data-aos="fade-up" data-aos-delay="100" data-aos-offset="200" data-aos-easing="ease-out-quart" class="aos-init aos-animate">
                    <img src="assets/images/icons/ceo.png" alt="" data-aos="fade-up" data-aos-delay="100" data-aos-offset="200" data-aos-easing="ease-out-quart" class="aos-init aos-animate">
                </figure>
                <br>
                <h4 class="title is-6"><?php echo $chief_executive_officer; ?></h4>
                <p><?php echo $chief_executive_officer_text; ?></p>
            </div>
        </div>
        <div class="breakline"></div>
    </section>
    <section class="hero is-fullheight" style="background:url('<?php echo base_url('assets/images/background BA-TRY.png'); ?>')">
        <div class="columns">
            <div class="column has-text-centered">
                <div class="breakline"></div>
                <div class="breakline"></div>
                <h1 data-aos="fade-right" data-aos-delay="200" data-aos-offset="200" data-aos-easing="ease-out-quart" class="aos-init aos-animate">
                    <span class="title has-text-centered text-gradient-limeblue">BLOW. YOUR. IMPACT.</span><br>
                    <span class="title has-text-centered text-gradient-impact" style="font-size:5rem">RIGHT NOW!</span>
                </h1>
                <br>
                <h1 data-aos="fade-up" data-aos-delay="300" data-aos-offset="200" data-aos-easing="ease-out-quart" class="aos-init aos-animate">
                    <a class="button custom-button" href="<?php echo base_url("register"); ?>"><span>TRY LeMonkey</span></a>
                </h1>
            </div>
        </div>
    </section>
    <section class="hero is-fullheight">
    </section>
    <style>
        .hero.is-info.is-bold {
            background: transparent;
        }
    </style>
