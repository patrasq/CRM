<?php $this->load->view('dashboard/menu'); ?>
<link href="<?= base_url("assets/css/font-awesome.min.css"); ?>">
<div class="column" style="padding:30px;">
    <div class="columns">
        <div class="column is-half">
            <br>
            <div class="group">
                <div class="field is-horizontal">
                    <div class="field-label is-normal">
                        <label class="label">First name</label>
                    </div>
                    <div class="field-body">
                        <div class="field">
                            <p class="control">
                                <input class="input is-static" type="text" value="<?php echo $first_name; ?>" readonly>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="field is-horizontal">
                    <div class="field-label is-normal">
                        <label class="label">Last name</label>
                    </div>
                    <div class="field-body">
                        <div class="field">
                            <p class="control">
                                <input class="input is-static" type="text" value="<?php echo $last_name; ?>" readonly>
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="field is-horizontal">
                    <div class="field-label is-normal">
                        <label class="label">EMail</label>
                    </div>
                    <div class="field-body">
                        <div class="field">
                            <p class="control">
                                <input class="input is-static" type="text" value="<?php echo $email; ?>" readonly>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <?php if(@$this->session->userdata("logged_in")["OAuth"] !== null): ?>
            <hr>
            <?php echo form_open("api/account/change_password/"); ?>
            <div class="group">
                <div class="field is-horizontal">
                    <div class="field-label is-normal">
                        <label class="label">Old password</label>
                    </div>
                    <div class="field-body">
                        <div class="field">
                            <p class="control">
                                <input name="oldpass" class="input" type="password" placeholder="Enter your current password...">
                            </p>
                        </div>
                    </div>
                </div>

                <div class="field is-horizontal">
                    <div class="field-label is-normal">
                        <label class="label">New password</label>
                    </div>
                    <div class="field-body">
                        <div class="field">
                            <p class="control">
                                <input name="newpass" class="input" type="password" placeholder="Enter your new password...">
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <button type="submit">Submit</button>
            <?php echo form_close(); ?>
            <?php endif; ?>
        </div>
        <div class="column">
        
        </div>
    </div>
</div>

<style>
    .input.is-static {
        padding-left: 10px;
    }
</style>
<?php $this->load->view('dashboard/footer'); ?>