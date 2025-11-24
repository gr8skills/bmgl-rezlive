<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
	<title><?= $title ?? 'Hotel | MakeIFly - Flight booking and Hotel Reservation' ?></title>
	<link rel="stylesheet" href="<?= base_url('assets/css/main.min.css') ?> ">
	<link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">


	<!-- jQuery UI (depends on jQuery) -->
	<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">


	<!-- flickity slider -->
	<link rel="stylesheet" href="https://unpkg.com/flickity@2/dist/flickity.min.css">

	<!-- Remix icon -->
	<link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">

	<!-- jQuery Calendar-->
	<link rel="stylesheet" href="<?= base_url('assets/pickmeup/css/pickmeup.css') ?>">

	<!-- Animate on scroll -->
	<link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />

	<script src="<?= base_url('assets/js/jquery-3.6.1.js') ?>"></script>
</head>
<body>

<!-- Header -->
<?php $this->load->view('partials/header'); ?>

<!-- Page Content -->
<main>
	<?= isset($content) ? $content : '' ?>
</main>

<!-- Footer -->
<?php $this->load->view('partials/footer'); ?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.5/dist/umd/popper.min.js"></script>
<script src="<?= base_url('assets/js/bootstrap.bundle.min.js') ?>"></script>
<script src="https://unpkg.com/flickity@2/dist/flickity.pkgd.min.js"></script>
<script src="<?= base_url('assets/js/slider.js') ?>"></script>
<script src="<?= base_url('assets/js/jquery.preloadinator.min.js') ?>"></script>
<script src="<?= base_url('assets/js/preloader.js') ?>"></script>

<!-- jQuery Calendar -->
<script src="<?= base_url('assets/pickmeup/js/pickmeup.js') ?>"></script>
<script src="<?= base_url('assets/js/date-picker.js') ?>"></script>
<script src="<?= base_url('assets/js/trip-selector.js') ?>"></script>

<script src="<?= base_url('assets/js/trip-selector.js') ?>"></script>
<script src="https://unpkg.com/aos@next/dist/aos.js"></script>
<script>
	AOS.init();
</script>

</body>
</html>
