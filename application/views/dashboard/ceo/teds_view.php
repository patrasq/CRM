<?php $this->load->view('dashboard/menu'); ?>

<link rel="stylesheet" href="<?= base_url("assets/css/font-awesome.min.css"); ?>" />

<div class="column" style="padding:30px;">
    
    <center><?= $pagination; ?></center>
    <br>
    <?php for($i = $current_stop; $i < $max_stop; $i++): ?>
        <div class="box">
            <article class="media">
                <div class="media-left">
                    <a href="<?= $ted[$i]["URL"]; ?>">
                        <figure class="image">
                            <img src="<?= $ted[$i]["Thumbnail"]; ?>" alt="Image">
                        </figure>
                    </a>
                </div>
                <div class="media-content">
                    <div class="content">
                        <div class="column">
                            <div class="columns">
                                <div class="column has-text-centered">
                                    <h2 class="title"><?= $ted[$i]["Name"]; ?></h2>
                                    <span>by <a target="_blank" href="https://www.google.com/search?client=firefox-b-d&q=<?= urlencode($ted[$i]["Author"]); ?>"><?= $ted[$i]["Author"]; ?></a></span>
                                    <br><br>
                                    <p><?= $ted[$i]["Description"]; ?></p>
                                </div>
                            </div>
                        </div>
                        <center>
                            <a class="button is-info" href="<?= $ted[$i]["URL"]; ?>">Watch TED</a>
                        </center>
                    </div>
                </div>
            </article>
        </div>
    <?php endfor; ?>
    
    <br>
    <center><?= $pagination; ?></center>
</div>

<?php $this->load->view('dashboard/footer'); ?>