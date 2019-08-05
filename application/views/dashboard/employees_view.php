<?php $this->load->view('dashboard/menu'); ?>
<div class="column">
    <br>
    <a href="#" class="button custom-button-normal open-modal" data-modal-id="#addemployee"><?php echo $text_add_employee; ?></a>
    <br><br>
    <table class="table" style="width:98%">
        <thead>
            <tr>
                <th>
                    <abbr title="Number">#</abbr>
                </th>
                <th>
                    <abbr>Name</abbr>
                </th>
                <th>
                    <abbr>Department</abbr>
                </th>
                <th>
                    <abbr>Position</abbr>
                </th>
                <th>
                    <abbr>View</abbr>
                </th>
            </tr>
        </thead>
        <tbody>
            <?php
            if($employee) {
                foreach($employee as $row) {
                    echo 
                        '<tr>'.
                        '<td>'.$row->ID.'</td>'.
                        '<td><a href="'.base_url("hr/employee/".$row->ID).'">'.$row->FirstName.' '.$row->LastName.'</a></td>'.
                        '<td>'.$row->Department.'</td>'.
                        '<td>'.$row->Position.'</td>'.
                        '<td><a href="'.base_url('hr/employee/'.$row->ID).'">Click</a></td>'.
                        '</tr>';
                }
            }
            ?>
        </tbody>
    </table>
</div>
<?php $this->load->view('dashboard/footer'); ?>

<div class="modal" id="addemployee">
    <div class="modal-background close-modal" data-modal-id="#addemployee"></div>
    <div class="modal-content">
        <div class="card">
            <div class="card-content">
                <div class="content">
                    <div class="columns">
                        <div class="column is-8">
                            <?php echo form_open("hr/add_employee/"); ?>
                            <div class="field">
                                <div class="control has-icons-left">
                                    <label for="department">First name</label>
                                    <input class="input is-rounded" name="firstname" placeholder="First name" type="text" autocomplete="off" required>
                                </div>
                            </div>                                                                                                               

                            <div class="field">
                                <div class="control has-icons-left">
                                    <label for="department">Last name</label>
                                    <input class="input is-rounded" name="lastname" placeholder="Last name" type="text" autocomplete="off" required>
                                </div>
                            </div>

                            <div class="field">
                                <div class="control has-icons-left">
                                    <label for="department">Department</label>
                                    <br>
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
                                    <label for="department">Position</label>
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

                            <button type="submit" class="custom-button-normal">add employee</button>
                            <?php echo form_close(); ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <button class="modal-close close-modal is-large" data-modal-id="#addemployee" aria-label="close"></button>
</div>

<script>
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

