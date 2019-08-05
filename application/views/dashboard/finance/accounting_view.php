<?php $this->load->view('dashboard/menu'); ?>

<div class="column" style="padding:30px;">
    <div class="columns">
        <div class="column">
            <h1 class="subtitle">
                Monthly income (+)
            </h1>
            <?php echo form_open("finance/add_accounting"); ?>
            <div class="field">
                <label class="label">Month</label>
                <div class="control has-icons-left">
                    <div class="select" style="width: 100%;">
                        <select class="input is-rounded select" name="month" required>
                            <option value="-1" disabled selected>Select the month</option>
                            <option value="january">January</option>
                            <option value="february">February</option>
                            <option value="march">March</option>
                            <option value="april">April</option>
                            <option value="may">May</option>
                            <option value="june">June</option>
                            <option value="july">July</option>
                            <option value="august">August</option>
                            <option value="september">September</option>
                            <option value="october">October</option>
                            <option value="november">November</option>
                            <option value="december">December</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="field">
                <label class="label">Income</label>
                <div class="control has-icons-left">
                    <input class="input is-static" type="text" placeholder="50,000" id="income_input">
                    <span class="icon is-small is-left">
                        <i class="fas fa-dollar-sign"></i>
                    </span>
                </div>
            </div>

            <button class="button is-info" type="submit">Submit</button>

            <?php echo form_close(); ?>
        </div>

        <!-- DASHED LINE -->
        <div class="column is-one-fifth" style="border-right:2px dashed #e7ecf3;flex:0"></div>

        <div class="column is-half" style="margin-left:15px;">
            <h1 class="subtitle">
                Monthly expenses (current month)
            </h1>
            <div class="columns">
                <div class="column">
                    <div class="field">
                        <label class="label">Expenses</label>
                        <div class="control has-icons-left">
                            <input name="expense_input" class="input is-static" type="text" placeholder="50,000" id="expense_input">
                            <span class="icon is-small is-left">
                                <i class="fas fa-dollar-sign"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="column">
                    <div class="field">
                        <label class="label">Reason</label>
                        <div class="control has-icons-left">
                            <input name="expense_reason" class="input is-static" type="text" placeholder="i.e salaries/supplies" id="expense_reason">
                            <span class="icon is-small is-left">
                                <i class="fas fa-dollar-sign"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="column is-one-fifth">
                    <span class="icon is-small is-left" style="margin-top:33px;margin-left: 15px;font-size: 15px;background: #d5d5d5;padding: 15px;border-radius: 100%;color: #747474;" id="add_expense">
                        <i class="fas fa-plus"></i>
                    </span>
                </div>
            </div>
            <label class="label">EXPENSES LIST</label>
            <input type="hidden" value="">
            <table class="table" style="width:90%;">
                <thead>
                    <th>Amount</th>
                    <th>Reason</th>
                    <th>Option</th>
                </thead>
                <tbody id="expenses_court">
                </tbody>
            </table>
            <a href="javascript:void(0)" class="button is-danger" id="submitexpenses">Submit expenses</a>
        </div>
    </div>
</div>
<input id="csrfHash"      type="hidden" value="<?= $this->security->get_csrf_hash(); ?>">
<input id="csrfTokenName" type="hidden" value="<?= $this->security->get_csrf_token_name(); ?>">


<script src="<?= base_url("assets/js/jquery.min.js"); ?>"></script>
<script src="<?= base_url("assets/js/jquery.mask.js"); ?>"></script>
<script>

    function decodeEntities(encodedString) {
        var textArea = document.createElement('textarea');
        textArea.innerHTML = encodedString;
        return textArea.value;
    }

    $("#expenses_court").html(decodeEntities("<?=$expenses_court;?>"));

    $('#income_input').mask('000,000,000,000,000', {reverse: true});
    $('#expense_input').mask('000,000,000,000,000', {reverse: true});
    var finance_encode = "{",
        i = 0,
        csrfHash    = '<?= $this->security->get_csrf_hash(); ?>',
        csrfName    = $('#csrfTokenName').val();

    $("#submitexpenses").on('click', function(){
        $("#add_expense").css("display", "none");
        $("#expense_input").attr("disabled", 1); 
        $("#expense_reason").attr("disabled", 1);


        finance_encode = "";

        $('#expenses_court > tr').each(function(e) {
            finance_encode += '{"expense":"'+($(this).children('td').eq(0).html()).replace(/,/g , '')+'","reason":"'+$(this).children('td').eq(1).html()+'"},';
        });

        finance_encode  = finance_encode.substring(0, finance_encode.length - 1);

        console.log(finance_encode);
        
        $.ajax(
            {
                type: "POST",
                url: "<?= base_url("finance/add_expenses/"); ?>",
                data: { 
                    json:          finance_encode,
                    [csrfName]:    $('#csrfHash').val()
                },
                success:function(response)
                {
                    $("#add_expense").css("display", "block");
                    $("#expense_input").attr("disabled", 0); 
                    $("#expense_reason").attr("disabled", 0);
                    //csrfHash = JSON.parse(response);
                    //$('#csrfHash').val(csrfHash.csrfHash);
                },
                error: function() 
                {
                    // document.location = "<?= base_url("ceo/agenda"); ?>";
                }
            }
        ); 
    });

    $("#add_expense").on('click', function(){
        i++;
        $("#expenses_court").html(
            $("#expenses_court").html() + 
            "<tr data-specificid='"+i+"'> " + 
            "<td>" + 
            $("#expense_input").val()  + 
            "</td>" +
            "<td>" +
            $("#expense_reason").val()  +
            "</td>" +
            "<td>" +
            "<span class='icon is-small is-left' onclick='remove_expense("+i+")' style='margin-left: 15px;font-size: 15px;background: #d5d5d5;padding: 15px;border-radius: 100%;color: #747474;'><i class='fas fa-trash'></i></span>" +
            "</td>" +
            "</tr>");
    });

    function remove_expense(data_id) {
        $("tr[data-specificid='" + data_id +"']").remove();
        finance_encode
    }

    $("#submitfinance").on('click', function(){
        event.preventDefault();
        var email= $("#email").val();

        $.ajax({
            type:"post",
            url: "<?= base_url("finance/add_accounting/"); ?>",
            data: {
                email: email
            },
            success:function(response)
            {
                console.log(response);
                $("#message").html(response);
                $('#cartmessage').show();
            },
            error: function() 
            {
                alert("Invalide!");
            }
        }); 
    });
</script>
<?php $this->load->view('dashboard/footer'); ?>