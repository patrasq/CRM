<br><br><br>
<footer class="footer">

            <div class="container">
                <div class="columns">
                    <div class="column has-text-left">
                        <p><?= $this->lang->line("main_switchlang"); ?> <a href="<?php echo base_url(); ?>LanguageSwitch/switch_language/<?php if($this->session->userdata("language") == "romanian") echo "english"; else echo "romanian"; ?>"><?php if($this->session->userdata("language") == "romanian") echo "english"; else echo "română"; ?></a></p>
                    </div>
                    <div class="column has-text-right">
                        <a href="<?= base_url("terms"); ?>"><?php echo $this->lang->line("main_tos"); ?></a>
                    </div>
                </div>
            </div>
        </footer>

        <script defer src="<?= base_url("assets/js/font-awesome.min.js");?>"></script>
        <script defer src="<?= base_url("assets/js/jquery.min.js");?>"></script>
        <script defer src="<?= base_url("assets/js/custom.js");?>"></script>

        <script>
            $(document).ready(function(){
                AOS.init();
            });

            function toggleModalClasses(event) {
                var modalId = event.currentTarget.dataset.modalId;
                var modal = $(modalId);
                modal.toggleClass('is-active');
                $('html').toggleClass('is-clipped');
            };

            $('.open-modal').click(toggleModalClasses);
            $('.close-modal').click(toggleModalClasses);

            function openTab(evt, tabName) {
                var i, x, tablinks;
                x = document.getElementsByClassName("content-tab");
                for (i = 0; i < x.length; i++) {
                    x[i].style.display = "none";
                }
                tablinks = document.getElementsByClassName("tab");
                for (i = 0; i < x.length; i++) {
                    tablinks[i].className = tablinks[i].className.replace(" is-active", "");
                }
                document.getElementById(tabName).style.display = "block";
                evt.currentTarget.className += " is-active";
            }
        </script>

    </body>
</html>