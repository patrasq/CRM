<body style="background:url('<?php echo base_url("assets/images/backgrounds/background_complete.png");?>') no-repeat;">
    <link href="<?= base_url("assets/css/font-awesome.min.css"); ?>">

    <div class="columns is-vcentered">
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
                    Add account
                </h1>
                <br>

                <?php echo form_open("Dashboard/add_user_2"); ?>
                <div class="field">
                    <label class="label">EMail</label>
                    <div class="control has-icons-left">
                        <input class="input is-rounded" name="email" placeholder="EMail" value="<?php echo set_value('email'); ?>" type="text" required>
                        <span class="icon is-small is-left">
                            <i class="fas fa-text-height"></i>
                        </span>
                    </div>
                </div>

                <div class="field">
                    <div class="control has-icons-left">
                        <label class='label' for="department">First name</label>
                        <input class="input is-rounded" name="firstname" placeholder="First name" type="text" autocomplete="off" required>
                    </div>
                </div>                                                                                                               

                <div class="field">
                    <div class="control has-icons-left">
                        <label class='label' for="department">Last name</label>
                        <input class="input is-rounded" name="lastname" placeholder="Last name" type="text" autocomplete="off" required>
                    </div>
                </div>

                <div class="field">
                    <div class="control has-icons-left">
                        <label class='label' for="department">Department</label>
                        <div class="select is-rounded">
                            <select name="department" id="departments" required>
                                <option value="-1" disabled selected>Select a department</option>
                                <?php if(!$department) echo "<option value='-1' disabled>You don't have any departments.</option>"; else foreach($department as $row): ?>

                                <option value="<?php echo $row->ID; ?>"><?php echo $row->Name; ?></option>

                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="field" id="positionfield" style="display:none">
                    <div class="control has-icons-left">
                        <label class='label' for="department">Position</label>
                        <br>
                        <div class="select is-rounded">
                            <select name="position" id="positionselect" required>

                            </select>
                        </div>
                    </div>
                </div>

                <div class="field">
                    <div class="control has-icons-left">
                        <label>Gender</label>
                        <br>
                        <div class="select is-rounded">
                            <select name="gender" required>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="field">
                    <div class="control has-icons-left">
                        <label>Phone</label>
                        <br>
                        <input class="input is-rounded" name="phone" placeholder="Phone" type="text" autocomplete="off" required>

                    </div>
                </div>

                <div class="field">
                    <div class="control has-icons-left">
                        <label>Address</label>
                        <br>
                        <input class="input is-rounded" name="address" placeholder="Address" type="text" autocomplete="off" required>
                    </div>
                </div>

                <div class="field">
                    <div class="control has-icons-left">
                        <label>Birth date</label>
                        <br>
                        <input class="input is-rounded" name="birthdate" placeholder="Birth date" type="date" autocomplete="off" required>

                    </div>
                </div>

                <div class="field">
                    <div class="control has-icons-left">
                        <label>Hired since</label>
                        <br>
                        <input class="input is-rounded" name="hiredsince" placeholder="Hired since" type="date" autocomplete="off" required>

                    </div>
                </div>
                
                <div class="field">
                    <div class="control has-icons-left">
                        <label>Type</label>
                        <br>
                        <div class="select is-rounded">
                            <select name="type">
                                <option value='1'>Employee</option>
                                <option value='2'>Supervisor</option>
                            </select>
                        </div>

                    </div>
                </div>

                <br>
                <div class="columns">
                    <div class="column">
                        <button type="submit" class="button is-vcentered is-rounded is-info" style="width: 200px;">Add user</button>
                    </div>
                </div>
                <br>
                <center><div class="g-recaptcha" data-sitekey="<?php echo $this->config->item('recaptcha_sitekey'); ?>"></div></center>

                <?php echo form_close(); ?>

            </section>
        </div>
    </div>
</body>

<script>
    function validate_url(url)
    {
        if (/^(https?:\/\/)?((w{3}\.)?)twitter\.com\/(#!\/)?[a-z0-9_]+$/i.test(url))
            return 'twitter';    

        if (/^(https?:\/\/)?((w{3}\.)?)facebook.com\/.*/i.test(url))
            return 'facebook';

        return 'unknown';
    }
    $('#departments').on('change', function() {
        //this.value
        $("#positionfield").css("display", "block");

        $.ajax({
            url: "<?php echo base_url("hr/get_positions/"); ?>" + this.value, 
            success:function(data) {
                $("#positionselect").html(data);
            }
        });
    });
</script>
