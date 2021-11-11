<?php
require_once '../app/init.php'
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Rakheoana Lefela | personal portfolio website</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
    <link href="https://cdn.lineicons.com/2.0/LineIcons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link rel="stylesheet" href="<?php url('assets/css/style.css')?>">
    <link rel="stylesheet" href="<?php url('assets/css/default.css')?>">
    <link rel="stylesheet" href="<?php url('assets/css/util.css')?>">
   
</head>
<body>
<?php include 'includes/header.php'; ?>
    <main>
        <?php $app = new App(); ?>
    </main>
<?php 
include 'includes/footer.php';
?>


<script src="<?php url('assets/js/vendor/jquery-1.12.4.min.js'); ?>"></script>
<script src="<?php url('assets/js/vendor/modernizr-3.6.0.min.js'); ?>"></script>
<script src=" <?php url('assets/js/bootstrap.min.js');?> "></script>
<script src="<?php url('assets/js/popper.min.js'); ?>"></script>
<script src="<?php url('assets/js/jquery.magnific-popup.min.js'); ?>"></script>
<script src="<?php url('assets/js/parallax.min.js'); ?>"></script>
<script src="<?php url('assets/js/waypoints.min.js'); ?>"></script>
<script src="<?php url('assets/js/jquery.counterup.min.js'); ?>"></script>
<script src="<?php url('assets/js/jquery.appear.min.js'); ?>"></script>
<script src="<?php url('assets/js/scrolling-nav.js'); ?>"></script>
<script src="<?php url('assets/js/jquery.easing.min.js'); ?>"></script>
<script src="<?php url('assets/js/main.js');?>"></script>

<script src="https://www.gstatic.com/firebasejs/8.2.9/firebase-app.js"></script>
<script src="https://www.gstatic.com/firebasejs/8.2.9/firebase-analytics.js"></script>

</body>
</html>



