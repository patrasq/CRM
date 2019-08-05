<?php $this->load->view('dashboard/menu'); ?>
<div class="column">
    <br>
    <h1 class="title" id="bigname"><?php echo $employee_name; ?></h1>
    <h2 class="subtitle" data-aos="fade-left" data-aos-delay="100" data-aos-offset="200" data-aos-easing="ease-out-quart" class="aos-init aos-animate"><?php echo $employee_position; ?> @ <?php echo $employee_department; ?></h2>

    <a href="<?= base_url("hr/delete_employee/" . $employee_id); ?>" class="button custom-button-normal" data-aos="fade-left" data-aos-delay="1000" data-aos-offset="200" data-aos-easing="ease-out-quart" class="aos-init aos-animate"><?php echo $text_delete_employee; ?></a>

    <br><br>  

    <nav class="tabs is-boxed is-fullwidth is-large" data-aos="fade-left" data-aos-delay="100" data-aos-offset="200" data-aos-easing="ease-out-quart" class="aos-init aos-animate">
        <div class="container">
            <ul>
                <li class="tab is-active" onclick="openTab(event,'general')"><a>General</a></li>
                <li class="tab" onclick="openTab(event,'finance')"><a>Finance</a></li>
                <li class="tab" onclick="openTab(event,'documents')"><a>Documents</a></li>
            </ul>
        </div>
    </nav>


    <div class="container section" style="margin-top: -25px;box-shadow:0px 0px 10px rgba(1, 1, 1, 0.18)"  data-aos="fade-left" data-aos-delay="100" data-aos-offset="200" data-aos-easing="ease-out-quart" class="aos-init aos-animate">
        <div id="general" class="content-tab" >

            <table class="table is-striped is-narrow is-hoverable is-fullwidth">
                <tbody>
                    <tr>
                        <th>Name</th>
                        <td><?php echo $employee_name; ?></td>
                    </tr>
                    <tr>
                        <th>Gender</th>
                        <td><?php echo $employee_gender; ?></td>
                    </tr>
                    <tr>
                        <th>Phone</th>
                        <td><?php echo $employee_phone; ?></td>
                    </tr>
                    <tr>
                        <th>Address</th>
                        <td><?php echo $employee_address; ?></td>
                    </tr>
                    <tr>
                        <th>Birth date</th>
                        <td><?php echo date("d M Y", $employee_birth); ?></td>
                    </tr>
                    <tr>
                        <th>Hired since</th>
                        <td><?php echo date("d M Y", $employee_hiredsince); ?></td>
                    </tr>
                </tbody>
            </table>

        </div>
        <div id="finance" class="content-tab" style="display:none">

            <table class="table is-striped is-narrow is-hoverable is-fullwidth">
                <tbody>
                    <tr>
                        <th>Account holder name</th>
                        <td><?php echo $employee_bank_holder; ?></td>
                    </tr>
                    <tr>
                        <th>Account number</th>
                        <td><?php echo $employee_bank_number; ?></td>
                    </tr>
                    <tr>
                        <th>Bank</th>
                        <td><?php echo $employee_bank_name; ?></td>
                    </tr>
                    <tr>
                        <th>Bank Identifier Number</th>
                        <td><?php echo $employee_bank_bin; ?></td>
                    </tr>
                </tbody>
            </table>

        </div>
        <div id="documents" class="content-tab" style="display:none">
            <table class="table is-striped is-narrow is-hoverable is-fullwidth">
                <tbody>
                    <tr>
                        <th>CV</th>
                        <td><?php echo $employee_documents_cv; ?></td>
                    </tr>
                    <tr>
                        <th>Contract</th>
                        <td><?php echo $employee_documents_ct; ?></td>
                    </tr>
                    <tr>
                        <th>ID Proof</th>
                        <td><?php echo $employee_documents_id; ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <br> <br> 

</div>
<?php $this->load->view('dashboard/footer'); ?>

<div class="modal" id="unmodal">
    <div class="modal-background close-modal" data-modal-id="#unmodal"></div>
    <div class="modal-content">
        <div class="card">
            <div class="card-content">
                <div class="content">
                    <div class="columns">
                        <div class="column is-8">
                            <div class="field">
                                <div class="control has-icons-right">
                                    <label for="department">Department name</label>
                                    <input class="input is-rounded" name="department" placeholder="Department name" value="<?php echo set_value('email'); ?>" type="email" autocomplete="off">
                                    <span class="icon is-small is-left">
                                        <i class="fa fa-envelope"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <button class="modal-close close-modal is-large" data-modal-id="#unmodal" aria-label="close"></button>
</div>

<div class="modal" id="adddepartment">
    <div class="modal-background close-modal" data-modal-id="#adddepartment"></div>
    <div class="modal-content">
        <div class="card">
            <div class="card-content">
                <div class="content">
                    <div class="columns">
                        <div class="column is-8">
                            <div class="field">
                                <div class="control has-icons-right">
                                    <label for="department">Department name</label>
                                    <input class="input is-rounded" name="department" placeholder="Department name" value="<?php echo set_value('email'); ?>" type="email" autocomplete="off">
                                    <span class="icon is-small is-left">
                                        <i class="fa fa-envelope"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <button class="modal-close close-modal is-large" data-modal-id="#adddepartment" aria-label="close"></button>
</div>

<style>
    #bigname {
        text-transform: uppercase;
        background: linear-gradient(to right,#0094ff 0,#00edfe 100%);
        background-clip: border-box;
        background-clip: border-box;
        background-clip: text;
        text-fill-color: transparent;
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        font-size: 70px;

    }
    .tabs li.is-active a {
        background: linear-gradient(45deg,#3690ff,#00d2ff 100%);
        color: #fff;
        text-transform: uppercase;
        font-family: 'Montserrat';
        margin-bottom: -20px;
        transition: .2s all;
    }
    .table th {
        text-transform: none;
        background: linear-gradient(to right,#0094ff 0,#00edfe 100%);
        text-fill-color: transparent;
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        font-size: 16px;
    }
</style>

<script>
    $(".notset_set").on('click', function() {
        $(this).parent().find('.editrow').css("display", "flex");
        $(this).css("display", "none");
    });

    $(".notset_revert").on('click', function(){
        $(this).parent().parent().parent().find('.editrow').css("display", "none");
        $(this).parent().parent().parent().find('.notset_set').css("display", "block");
    });
</script>