<?php $this->load->view('dashboard/menu'); ?>
<link href="<?= base_url("assets/css/font-awesome.min.css"); ?>">
<div class="column" style="padding:30px;">
    <div class="columns">

        <!-- BUSINESS INFO -->
        <div class="column is-two-fifths">
            
            <br>
            <h1 class="title titlelight">
                Set up your business
            </h1>
            <br>
            <?= form_open(); ?>
            <div class="field">
                <label class="label">Company name</label>
                <div class="control has-icons-left">
                    <input class="input is-rounded" name="name" placeholder="Company name (comercial)" value="<?= $business_name; ?>" type="text" required>
                    <span class="icon is-small is-left">
                        <i class="fas fa-text-height"></i>
                    </span>
                </div>
            </div>
            <?= form_error('name'); ?>

            <div class="field">
                <label class="label">Register country</label>
                <div class="control has-icons-left">
                    <div class="select">
                        <select class="input is-rounded select" name="country" style="width: 75vw;" required>
                            <option value="-1" disabled selected>Select the country where your business is registered</option>
                            <?php for($i = 0; $i < sizeof($countries); $i++): ?>
                            <option value="<?= $countries[$i]; ?>" <?php if($countries[$i] == $business_country) echo "selected"; ?>><?= $countries[$i]; ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <span class="icon is-small is-left">
                        <i class="fas fa-globe"></i>
                    </span>
                </div>
            </div>
            <?= form_error('country'); ?>

            <div class="field">
                <label class="label">Activity field</label>
                <div class="control has-icons-left">
                    <div class="select">
                        <select class="input is-rounded select" name="activity" style="width: 75vw;" required>
                            <option disabled selected>Activity field</option>
                            <?php for($i = 0; $i < sizeof($activity_field); $i++): ?>
                            <option value="<?= $activity_field[$i]; ?>" <?php if($activity_field[$i] == $business_activity) echo "selected"; ?>><?= $activity_field[$i]; ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <span class="icon is-small is-left">
                        <i class="fas fa-globe"></i>
                    </span>
                </div>
            </div>
            <?= form_error('activity'); ?>

            <div class="field">
                <label class="label">Description</label>
                <div class="control has-icons-left">
                    <textarea class="textarea is-rounded has-fixed-size" placeholder="Describe your company..." name="description" rows="2" required><?= $business_description; ?></textarea>
                </div>
            </div>
            <?= form_error('description');?>

            <div class="field">
                <label class="label">Website</label>
                <div class="control has-icons-left">
                    <input class="input is-rounded" name="website" placeholder="Website link" value="<?= $business_website; ?>" type="text" autocomplete="on">
                    <span class="icon is-small is-left">
                        <i class="fa fa-globe"></i>
                    </span>
                </div>
            </div>
            <?= form_error('website'); ?>

            <br>

            <div class="columns">
                <div class="column">
                    <button type="submit" class="button is-vcentered is-rounded is-info" style="width: 200px;">Edit company</button>
                </div>
            </div>

            <?= form_close(); ?>
        </div>

        <!-- DASHED LINE -->
        <div class="column is-one-fifth" style="border-right:2px dashed #e7ecf3;flex:0"></div>

        <!-- SOCIAL MEDIA COLUMN -->
        <div class="column is-two-fifths" style="margin-left:15px;">
            <br>
            <h1 class="title titlelight">
                Set up your social media
            </h1>
            <br>

            <?= form_open("settings/business_sm"); ?>
            <div class="field">
                <label class="label">Facebook</label>
                <div class="control has-icons-left">
                    <input class="input is-rounded" name="facebook" placeholder="Facebook link" value="<?= $business_facebook; ?>" type="text" autocomplete="on">
                    <span class="icon is-small is-left">
                        <i class="fab fa-facebook-f"></i>
                    </span>
                </div>
            </div>
            <?= form_error('facebook'); ?>

            <div class="field">
                <label class="label">Instagram</label>
                <div class="control has-icons-left">
                    <input class="input is-rounded" name="instagram" placeholder="Instagram link" value="<?= $business_instagram; ?>" type="text" autocomplete="on">
                    <span class="icon is-small is-left">
                        <i class="fab fa-instagram"></i>
                    </span>
                </div>
            </div>
            <?= form_error('instagram'); ?>

            <div class="field">
                <label class="label">Twitter</label>
                <div class="control has-icons-left">
                    <input class="input is-rounded" name="twitter" placeholder="Twitter link" value="<?= $business_twitter; ?>" type="text" autocomplete="on">
                    <span class="icon is-small is-left">
                        <i class="fab fa-twitter"></i>
                    </span>
                </div>
            </div>
            <?= form_error('twitter'); ?>
            
            <div class="field">
                <label class="label">Facebook hashtag</label>
                <div class="control has-icons-left">
                    <input class="input is-rounded" id="facebook_hashtag" name="facebook_hashtag" placeholder="Facebook hashtag (i.e #mycompany)" value="<?= $facebook_hashtag; ?>" type="text">
                    <span class="icon is-small is-left">
                        <i class="fab fa-facebook-f"></i>
                    </span>
                </div>
            </div>
            <?= form_error('facebook_hashtag'); ?>
            
            <div class="field">
                <label class="label">Instagram hashtag</label>
                <div class="control has-icons-left">
                    <input class="input is-rounded" id="instagram_hashtag" name="instagram_hashtag" placeholder="Instagram hashtag (i.e #mycompany)" value="<?= $instagram_hashtag; ?>" type="text">
                    <span class="icon is-small is-left">
                        <i class="fab fa-instagram"></i>
                    </span>
                </div>
            </div>
            <?= form_error('instagram_hashtag'); ?>
            
            <div class="field">
                <label class="label">Twitter hashtag</label>
                <div class="control has-icons-left">
                    <input class="input is-rounded" id="twitter_hashtag" name="twitter_hashtag" placeholder="Twitter hashtag (i.e #mycompany)" value="<?= $twitter_hashtag; ?>" type="text">
                    <span class="icon is-small is-left">
                        <i class="fab fa-twitter"></i>
                    </span>
                </div>
            </div>
            <?= form_error('twitter_hashtag'); ?>

            <br>

            <div class="columns">
                <div class="column">
                    <button type="submit" class="button is-vcentered is-rounded is-info" style="width: 200px;">Edit social media</button>
                </div>
            </div>
            
            <?= form_close(); ?>
        </div>
    </div>
</div>

<style>
    .input.is-static {
        padding-left: 10px;
    }
</style>

<script>
    $("#facebook_hashtag").focusout(function(){
        var regex = new RegExp("\\(\#[a-zA-Z]+\b)(?!;)/gm");
        if(regex.test($("#facebook_hashtag").html())) alert("YEE");
    });
</script>

<?php $this->load->view('dashboard/footer'); ?>