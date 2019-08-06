<!DOCTYPE html>
<html>
    <head>
        <meta charset="<?php echo $this->config->item('charset'); ?>">
        <!--<script async src="https://cdn.ampproject.org/v0.js"></script>-->
        <!--<link rel="canonical" href="<?php echo base_url(); ?>">-->
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?php echo $this->config->item('site_name'); ?></title>
        <meta name="description" content="LeMonkey is a all-in-one tool to enhance your website. Speed tests, security audit, seo helper and code validator.">
        <meta name="keywords" content="LeMonkey,website enhance,website">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="theme-color" content="#FFFFFF">
        <meta name="application-name" content="LeMonkey" />
        <meta name="msapplication-TileColor" content="#a33486" />

        <link rel="apple-touch-icon" href="https://www.kwindo.ro/assets/images/touch-icon-iphone-retina.png">
        <link rel="apple-touch-icon" sizes="152x152" href="https://www.kwindo.ro/assets/images/touch-icon-ipad.png">
        <link rel="apple-touch-icon" sizes="180x180" href="https://www.kwindo.ro/assets/images/touch-icon-iphone-retina.png">
        <link rel="apple-touch-icon" sizes="167x167" href="https://www.kwindo.ro/assets/images/touch-icon-ipad-retina.png">
        <link rel="shortcut icon" type="image/png" href="https://www.kwindo.ro/assets/images/favicon.png" />

        <meta property="og:type" content="website" />
        <meta property="og:title" content="<?php echo $this->config->item('site_name'); ?>" />
        <meta property="og:description" content="LeMonkey is a all-in-one tool to enhance your website. Speed tests, security audit, seo helper and code validator." />
        <meta property="og:url" content="<?php echo base_url(); ?>" />
        <meta property="og:site_name" content="<?php echo $this->config->item('site_name'); ?>" />
        <meta property="og:image" content="https://www.kwindo.ro/assets/images/opengraphCreateSite.png" />
        <meta property="og:image:type" content="image/png" />
        <meta property="og:image:width" content="1200" />
        <meta property="og:image:height" content="628" />
        <meta property="og:updated_time" content="1525294697" />


        <meta property="article:publisher" content="https://www.facebook.com/kwindoro">
        <meta property="article:author" content="https://www.facebook.com/kwindoro">

        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:description" content="LeMonkey is a all-in-one tool to enhance your website. Speed tests, security audit, seo helper and code validator.">
        <meta name="twitter:site" content="@LeMonkey">
        <meta name="twitter:creator" content="@LeMonkey">
        <meta name="twitter:title" content="<?php echo $this->config->item('site_name'); ?>">
        <meta name="twitter:image:src" content="https://www.kwindo.ro/assets/images/opengraphCreateSite.png">

        <link href="https://fonts.googleapis.com/css?family=Montserrat:400,600|Open+Sans" rel="stylesheet"> 

        <link rel="stylesheet" href="<?php echo base_url("assets/css/bulma.min.css"); ?>">
        <link rel="stylesheet" href="<?php echo base_url("assets/css/custom.css"); ?>">

        <link href="<?= base_url("assets/css/aos.min.css"); ?>" rel="stylesheet">
        <script src="<?= base_url("assets/js/aos.min.js"); ?>"></script>
        <script src="<?= base_url("assets/js/jquery.min.js"); ?>"></script>

        <script type="application/ld+json">
         {
              "@context": "http://schema.org",
              "@type": "LocalBusiness",
              "email": "mailto:info@LeMonkey.ro",
              "description": "LeMonkey is a all-in-one tool to enhance your website. Speed tests, security audit, seo helper and code validator.",
              "name": "<?php echo $this->config->item('site_name'); ?>",
              "url": "<?php echo base_url(); ?>",
              "parentOrganization": "Kwindo Studios"
         }
        </script>

        <!--<style amp-boilerplate>body{-webkit-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-moz-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-ms-animation:-amp-start 8s steps(1,end) 0s 1 normal both;animation:-amp-start 8s steps(1,end) 0s 1 normal both}@-webkit-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-moz-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-ms-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-o-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}</style><noscript><style amp-boilerplate>body{-webkit-animation:none;-moz-animation:none;-ms-animation:none;animation:none}</style></noscript>-->

        <?php if($this->uri->segment(1) == "login"): ?>
        <!--<script src='https://www.google.com/recaptcha/api.js'></script>-->
        <?php endif; ?>

        <link rel="manifest" href="/manifest.json" />
        <script src="https://cdn.onesignal.com/sdks/OneSignalSDK.js" async=""></script>
        <script>
            var OneSignal = window.OneSignal || [];
            OneSignal.push(function() {
                OneSignal.init({
                    appId: "cc85030b-ce95-48bc-8d44-94905592bf15",
                });
            });
            OneSignal.push(function() {
                OneSignal.getUserId(function(userId) {
                    $.ajax({
                        type:"post",
                        url: "<?= base_url("dashboard/register_push/"); ?>",
                        data: {
                            user_id: userId
                        },
                        success:function(response)
                        {
                            
                        }
                    }); 
                });
            });
        </script>
    </head>