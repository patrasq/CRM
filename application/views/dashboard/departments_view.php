<?php $this->load->view('dashboard/menu'); ?>
<div class="column">
    <br>
    <a href="#" class="button custom-button-normal open-modal" data-modal-id="#adddepartment"><?= $text_add_department; ?></a>
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
                    <abbr>Positions</abbr>
                </th>
                <th>
                    <abbr>Actions</abbr>
                </th>
            </tr>
        </thead>
        <tbody>
            <?php
            if($department) {
                foreach($department as $row) {
                    echo 
                        '<tr data-dep="'.$row->ID.'">'.
                        '<td>'.$row->ID.'</td>'.
                        '<td data-dep="'.$row->ID.'" data-name=\'' . ($row->Name) . '\'>'.$row->Name.'</td>'.
                        '<td data-dep="'.$row->ID.'" data-positions=\'' . str_replace("|", ",", str_replace("'", "", $row->Positions)). '\'>'.str_replace('|', '<br>', $row->Positions).'</td>'.
                        '<td><a class="open-modal" data-dep="'.$row->ID.'" data-modal-id="#editdepartment">Edit</a></td>'.
                        '</tr>';
                }
            }
            ?>
        </tbody>
    </table>
</div>
<?php $this->load->view('dashboard/footer'); ?>

<div class="modal" id="adddepartment">
    <div class="modal-background close-modal" data-modal-id="#adddepartment"></div>
    <div class="modal-content">
        <div class="card">
            <div class="card-content">
                <div class="content">
                    <div class="columns">
                        <div class="column is-8">
                            <?= form_open("hr/add_department/"); ?>
                            <div class="field">
                                <div class="control has-icons-left">
                                    <label for="department">Department name</label>
                                    <input class="input is-rounded" name="departmentname" placeholder="Department name" type="text" autocomplete="off" required>
                                </div>
                            </div>                                                                                                               

                            <div class="field">
                                <div class="control has-icons-left">
                                    <label for="department">Description</label>
                                    <input class="input is-rounded" name="description" placeholder="Description" type="text" autocomplete="off" required>
                                </div>
                            </div>

                            <div class="field">
                                <div class="control has-icons-left">
                                    <label for="department">Positions (split by comma)</label>
                                    <input class="input is-rounded" name="positions" placeholder="Web developer,Janitor,Horseman,Medic" type="text" autocomplete="off" required>
                                </div>
                            </div>

                            <button type="submit" class="custom-button-normal">add department</button>
                            <?= form_close(); ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <button class="modal-close close-modal is-large" data-modal-id="#adddepartment" aria-label="close"></button>
</div>


<div class="modal" id="editdepartment">
    <div class="modal-background close-modal" data-modal-id="#editdepartment"></div>
    <div class="modal-content">
        <div class="card">
            <div class="card-content">
                <div class="content">
                    <div class="columns">
                        <div class="column is-12">
                            <?= form_open("hr/edit_department/"); ?>
                            <input type="hidden" id="depid" name="depid">
                            <div class="field">
                                <div class="control has-icons-left">
                                    <label for="department">Department name</label>
                                    <input class="input is-rounded" name="set_departmentname" id="set_departmentname" placeholder="Department name" type="text" autocomplete="off" required>
                                </div>
                            </div>                                                                                                               

                            <div class="field">
                                <div class="control has-icons-left">
                                    <label for="department">Positions (split by comma)</label>
                                    <input class="input is-rounded" name="set_positions" id="set_positions" placeholder="Department name" type="text" autocomplete="off" required>
                                </div>
                            </div>

                            <button type="submit" class="custom-button-normal">edit department</button>
                            <?= form_close(); ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <button class="modal-close close-modal is-large" data-modal-id="#editdepartment" aria-label="close"></button>
</div>

<script>
    $(".open-modal").on('click', function(){
        var dep_name    =   $("td[data-dep="+$(this).data("dep")+"]").data("name"),
            positions   =   $("td[data-dep="+$(this).data("dep")+"][data-positions]").data("positions"),
            depid       =   $("td[data-dep="+$(this).data("dep")+"]").data("dep");
        $("#set_departmentname").attr("value", dep_name); 
        $("#set_positions").attr("value", positions); 
        $("#depid").attr("value", depid); 
    });
</script>