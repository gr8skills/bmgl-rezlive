<?php
// Get booking confirmation from session
$confirmation = $this->session->userdata('booking_confirmation');
$email = $this->session->userdata('booking_email');
$phone = $this->session->userdata('booking_phone');
$guestName = $this->session->userdata('booking_guest_name') ?: 'Guest';

// Parse dates for display
$checkInDate = isset($confirmation['arrivalDate']) ? DateTime::createFromFormat('d/m/Y', $confirmation['arrivalDate']) : new DateTime();
$checkOutDate = isset($confirmation['departureDate']) ? DateTime::createFromFormat('d/m/Y', $confirmation['departureDate']) : new DateTime('+1 day');

// Calculate total in Naira
$totalRate = isset($confirmation['totalRate']) ? (float)$confirmation['totalRate'] : 0;
$totalNaira = $totalRate * USD_TO_NGN_RATE;
?>

<!-- Hotel booking ticket -->
<section>
	<div class="container">
		<div class="row" data-aos="fade-up" data-aos-duration="1000">
			<div class="col-lg-4 m-auto mb-5 text-center">
				<img src="<?= base_url('assets/images/flight-deals/success.svg') ?>" alt="">
				<h3 class="text-success fw-bold mt-4">Your booking is done!</h3>
				<p>Thank you for booking with MakeIFly!
					Your booking confirmation will be sent to <?= htmlspecialchars($email) ?>.
					You will be contacted as soon as your booking is confirmed.</p>
			</div>
		</div>

		<div class="row">
			<div class="col-lg-7 m-auto">
				<div class="card">
					<div class="card-header bg-warning text-center" data-aos="fade-up" data-aos-duration="1000">
						<h5 class="fw-bold my-auto"><?= htmlspecialchars($confirmation['hotelName'] ?? 'Hotel') ?> Booking Ticket</h5>
					</div>
					<div class="card-body" data-aos="fade-up" data-aos-duration="1000">
						<?php if (!empty($confirmation['bookingRefNo'])): ?>
						<div class="alert alert-success text-center">
							<strong>Booking Reference:</strong> <?= htmlspecialchars($confirmation['bookingRefNo']) ?>
						</div>
						<?php endif; ?>

						<div class="row g-3 mt-2">
							<div class="col-lg-3 col-6">
								<p class="small mb-n1">Name of Guest:</p>
								<p class="fw-bold"><?= htmlspecialchars($guestName) ?></p>
							</div>
							<div class="col-lg-3 col-6">
								<p class="small mb-n1">Email Address:</p>
								<p class="fw-bold"><?= htmlspecialchars($email) ?></p>
							</div>
							<div class="col-lg-3 col-6">
								<p class="small mb-n1">Phone Number:</p>
								<p class="fw-bold"><?= htmlspecialchars($phone) ?></p>
							</div>
							<div class="col-lg-3 col-6">
								<p class="small mb-n1">Total Payment:</p>
								<p class="fw-bold"><?= DISPLAY_CURRENCY_SYMBOL ?><?= number_format($totalNaira, 2) ?></p>
							</div>
						</div>

						<div class="row g-3 mt-2">
							<div class="col-lg-3 col-6">
								<p class="small mb-n1">Check-in:</p>
								<p class="fw-bold"><?= $checkInDate ? $checkInDate->format('F d, Y') : 'N/A' ?></p>
							</div>
							<div class="col-lg-3 col-6">
								<p class="small mb-n1">Check-out:</p>
								<p class="fw-bold"><?= $checkOutDate ? $checkOutDate->format('F d, Y') : 'N/A' ?></p>
							</div>
							<div class="col-lg-3 col-6">
								<p class="small mb-n1">Check-in Time:</p>
								<p class="fw-bold">2:00 PM</p>
							</div>
							<div class="col-lg-3 col-6">
								<p class="small mb-n1">Status:</p>
								<p class="fw-bold"><span class="badge bg-success"><?= htmlspecialchars($confirmation['status'] ?? 'Confirmed') ?></span></p>
							</div>
						</div>

						<div class="row mt-4">
							<div class="col-12 text-center">
								<a href="<?= site_url('home') ?>" class="btn btn-primary px-5">
									<i class="ri-home-line"></i> Back to Home
								</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
<!-- Hotel booking ticket -->


<!-- Flight deals -->
<section class="flight-deals" data-aos="fade-up" data-aos-duration="1000">
	<div class="container">
		<div class="row">
			<div class="col-md-12 mt-5 mb-3">
				<h1 class="big-heading">Check out our flight deals</h1>
			</div>
			<!-- flight-deal slider -->
			<div class="main-gallery js-flickity"
				data-flickity-options='{ "cellAlign": "center", "contain": "true", "freeScroll": "true", "wrapAround": true }'>
				<!-- carousel 1  -->
				<div class="card carousel-cell border-0 rounded my-3" style="width: 18rem;">
					<img src="<?= base_url('assets/images/flight-deals/flight-deal-1.png') ?>" class="card-img-top" alt="...">
					<div class="card-body">
						<h5 class="fw-bold">Lagos - Dubai</h5>
						<p>May 6, 2022 - May 9,2022</p>
					</div>
					<div class="card-footer bg-transparent border-0">
						<div class="row">
							<div class="d-flex align-items-center">
								<div class="me-auto">
									<p class="fw-semibold">Economy from Arik Air</p>
									<h4 class="fw-bold text-success my-auto">N350,000</h4>
								</div>
								<div class="ms-auto">
									<img src="<?= base_url('assets/images/flight-deals/Apeace-2.png') ?>" width="70" height="70" alt="">
								</div>
							</div>
							<a href="#" class="stretched-link"></a>
						</div>
					</div>
				</div>
				<!-- carousel 1 -->

				<!-- carousel 2  -->
				<div class="card carousel-cell border-0 rounded my-3" style="width: 18rem;">
					<img src="<?= base_url('assets/images/flight-deals/flight-deal-2.png') ?>" class="card-img-top" alt="...">
					<div class="card-body">
						<h5 class="fw-bold">Lagos - Dubai</h5>
						<p>May 6, 2022 - May 9,2022</p>
					</div>
					<div class="card-footer bg-transparent border-0">
						<div class="row">
							<div class="d-flex align-items-center">
								<div class="me-auto">
									<p class="fw-semibold">Economy from Arik Air</p>
									<h4 class="fw-bolder text-success">N350,000</h4>
								</div>
								<div class="ms-auto">
									<img src="<?= base_url('assets/images/flight-deals/Apeace-2.png') ?>" width="70" height="70" alt="">
								</div>
							</div>
							<a href="#" class="stretched-link"></a>
						</div>
					</div>
				</div>
				<!-- carousel 2 -->

				<!-- carousel 3  -->
				<div class="card carousel-cell border-0 rounded my-3" style="width: 18rem;">
					<img src="<?= base_url('assets/images/flight-deals/flight-deal-3.png') ?>" class="card-img-top" alt="...">
					<div class="card-body">
						<h5 class="fw-bold">Lagos - Dubai</h5>
						<p>May 6, 2022 - May 9,2022</p>
					</div>
					<div class="card-footer bg-transparent border-0">
						<div class="row">
							<div class="d-flex align-items-center">
								<div class="me-auto">
									<p class="fw-semibold">Economy from Arik Air</p>
									<h4 class="fw-bolder text-success">N350,000</h4>
								</div>
								<div class="ms-auto">
									<img src="<?= base_url('assets/images/flight-deals/Apeace-2.png') ?>" width="70" height="70" alt="">
								</div>
							</div>
							<a href="#" class="stretched-link"></a>
						</div>
					</div>
				</div>
				<!-- carousel 3 -->

				<!-- carousel 4  -->
				<div class="card carousel-cell border-0 rounded my-3" style="width: 18rem;">
					<img src="<?= base_url('assets/images/flight-deals/flight-deal-4.png') ?>" class="card-img-top" alt="...">
					<div class="card-body">
						<h5 class="fw-bold">Lagos - Dubai</h5>
						<p>May 6, 2022 - May 9,2022</p>
					</div>
					<div class="card-footer bg-transparent border-0">
						<div class="row">
							<div class="d-flex align-items-center">
								<div class="me-auto">
									<p class="fw-semibold">Economy from Arik Air</p>
									<h4 class="fw-bolder text-success">N350,000</h4>
								</div>
								<div class="ms-auto">
									<img src="<?= base_url('assets/images/flight-deals/Apeace-2.png') ?>" width="70" height="70" alt="">
								</div>
							</div>
							<a href="#" class="stretched-link"></a>
						</div>
					</div>
				</div>
				<!-- carousel 4 -->

				<!-- carousel 5  -->
				<div class="card carousel-cell border-0 rounded my-3" style="width: 18rem;">
					<img src="<?= base_url('assets/images/flight-deals/flight-deal-1.png') ?>" class="card-img-top" alt="...">
					<div class="card-body">
						<h5 class="fw-bold">Lagos - Dubai</h5>
						<p>May 6, 2022 - May 9,2022</p>
					</div>
					<div class="card-footer bg-transparent border-0">
						<div class="row">
							<div class="d-flex align-items-center">
								<div class="me-auto">
									<p class="fw-semibold">Economy from Arik Air</p>
									<h4 class="fw-bolder text-success">N350,000</h4>
								</div>
								<div class="ms-auto">
									<img src="<?= base_url('assets/images/flight-deals/Apeace-2.png') ?>" width="70" height="70" alt="">
								</div>
							</div>
							<a href="#" class="stretched-link"></a>
						</div>
					</div>
				</div>
				<!-- carousel 5 -->

				<!-- carousel 6  -->
				<div class="card carousel-cell border-0 rounded my-3" style="width: 18rem;">
					<img src="<?= base_url('assets/images/flight-deals/flight-deal-2.png') ?>" class="card-img-top" alt="...">
					<div class="card-body">
						<h5 class="fw-bold">Lagos - Dubai</h5>
						<p>May 6, 2022 - May 9,2022</p>
					</div>
					<div class="card-footer bg-transparent border-0">
						<div class="row">
							<div class="d-flex align-items-center">
								<div class="me-auto">
									<p class="fw-semibold">Economy from Arik Air</p>
									<h4 class="fw-bolder text-success">N350,000</h4>
								</div>
								<div class="ms-auto">
									<img src="<?= base_url('assets/images/flight-deals/Apeace-2.png') ?>" width="70" height="70" alt="">
								</div>
							</div>
							<a href="#" class="stretched-link"></a>
						</div>
					</div>
				</div>
				<!-- carousel 6 -->

				<!-- carousel 7  -->
				<div class="card carousel-cell border-0 rounded my-3" style="width: 18rem;">
					<img src="<?= base_url('assets/images/flight-deals/flight-deal-3.png') ?>" class="card-img-top" alt="...">
					<div class="card-body">
						<h5 class="fw-bold">Lagos - Dubai</h5>
						<p>May 6, 2022 - May 9,2022</p>
					</div>
					<div class="card-footer bg-transparent border-0">
						<div class="row">
							<div class="d-flex align-items-center">
								<div class="me-auto">
									<p class="fw-semibold">Economy from Arik Air</p>
									<h4 class="fw-bolder text-success">N350,000</h4>
								</div>
								<div class="ms-auto">
									<img src="<?= base_url('assets/images/flight-deals/Apeace-2.png') ?>" width="70" height="70" alt="">
								</div>
							</div>
							<a href="#" class="stretched-link"></a>
						</div>
					</div>
				</div>
				<!-- carousel 7 -->

				<!-- carousel 8  -->
				<div class="card carousel-cell border-0 rounded my-3" style="width: 18rem;">
					<img src="<?= base_url('assets/images/flight-deals/flight-deal-4.png') ?>" class="card-img-top" alt="...">
					<div class="card-body">
						<h5 class="fw-bold">Lagos - Dubai</h5>
						<p>May 6, 2022 - May 9,2022</p>
					</div>
					<div class="card-footer bg-transparent border-0">
						<div class="row">
							<div class="d-flex align-items-center">
								<div class="me-auto">
									<p class="fw-semibold">Economy from Arik Air</p>
									<h4 class="fw-bolder text-success">N350,000</h4>
								</div>
								<div class="ms-auto">
									<img src="<?= base_url('assets/images/flight-deals/Apeace-2.png') ?>" width="70" height="70" alt="">
								</div>
							</div>
							<a href="#" class="stretched-link"></a>
						</div>
					</div>
				</div>
				<!-- carousel 8 -->
			</div>
			<!-- flight-deal slider -->
		</div>
</section>
<!-- Flight deals -->

<!-- Vacation -->
<section class="vacation-packages" data-aos="fade-up" data-aos-duration="1000">
	<div class="container">
		<div class="row">
			<div class="col-md-12 mt-5 mb-3">
				<h3 class="fw-bold">
					<div class="col-md-12 mt-5 mb-3">
						<h1 class="big-heading">Enjoy a sweet vacation package</h1>
					</div>
				</h3>
			</div>
			<!-- vacation-package slider -->
			<div class="main-gallery js-flickity mb-5"
				data-flickity-options='{ "cellAlign": "left", "contain": "true", "freeScroll": "true", "wrapAround": true }'>
				<!-- carousel 1  -->
				<div class="card carousel-cell border-0 rounded my-3">
					<img src="<?= base_url('assets/images/vacation-packages/vacation-1.png') ?>" class="card-img" width="100%" height="100%" alt="Vacation Image">
					<div class="img-overlay">
						<div class="position-absolute bottom-0 start-0 mx-2">
							<h4 class="fw-bold text-white">Accra</h4>
							<p class="fw-semibold text-white">156 Things to do</p>
						</div>
					</div>
					<a href="#" class="stretched-link"></a>
				</div>
				<!-- carousel 1 -->

				<!-- carousel 2  -->
				<div class="card carousel-cell border-0 rounded my-3">
					<img src="<?= base_url('assets/images/vacation-packages/vacation-2.png') ?>" class="card-img" width="100%" height="100%" alt="Vacation Image">
					<div class="img-overlay">
						<div class="position-absolute bottom-0 start-0 mx-2">
							<h4 class="fw-bold text-white">Accra</h4>
							<p class="fw-semibold text-white">156 Things to do</p>
						</div>
					</div>
					<a href="#" class="stretched-link"></a>
				</div>
				<!-- carousel 2 -->

				<!-- carousel 3  -->
				<div class="card carousel-cell border-0 rounded my-3">
					<img src="<?= base_url('assets/images/vacation-packages/vacation-3.png') ?>" class="card-img" width="100%" height="100%" alt="Vacation Image">
					<div class="img-overlay">
						<div class="position-absolute bottom-0 start-0 mx-2">
							<h4 class="fw-bold text-white">Accra</h4>
							<p class="fw-semibold text-white">156 Things to do</p>
						</div>
					</div>
					<a href="#" class="stretched-link"></a>
				</div>
				<!-- carousel 3 -->

				<!-- carousel 4  -->
				<div class="card carousel-cell border-0 rounded my-3">
					<img src="<?= base_url('assets/images/vacation-packages/vacation-1.png') ?>" class="card-img" width="100%" height="100%" alt="Vacation Image">
					<div class="img-overlay">
						<div class="position-absolute bottom-0 start-0 mx-2">
							<h4 class="fw-bold text-white">Accra</h4>
							<p class="fw-semibold text-white">156 Things to do</p>
						</div>
					</div>
					<a href="#" class="stretched-link"></a>
				</div>
				<!-- carousel 4 -->

				<!-- carousel 5  -->
				<div class="card carousel-cell border-0 rounded my-3">
					<img src="<?= base_url('assets/images/vacation-packages/vacation-1.png') ?>" class="card-img" width="100%" height="100%" alt="Vacation Image">
					<div class="img-overlay">
						<div class="position-absolute bottom-0 start-0 mx-2">
							<h4 class="fw-bold text-white">Accra</h4>
							<p class="fw-semibold text-white">156 Things to do</p>
						</div>
					</div>
					<a href="#" class="stretched-link"></a>
				</div>
				<!-- carousel 5 -->

				<!-- carousel 6  -->
				<div class="card carousel-cell border-0 rounded my-3">
					<img src="<?= base_url('assets/images/vacation-packages/vacation-2.png') ?>" class="card-img" width="100%" height="100%" alt="Vacation Image">
					<div class="img-overlay">
						<div class="position-absolute bottom-0 start-0 mx-2">
							<h4 class="fw-bold text-white">Accra</h4>
							<p class="fw-semibold text-white">156 Things to do</p>
						</div>
					</div>
					<a href="#" class="stretched-link"></a>
				</div>
				<!-- carousel 6 -->

				<!-- carousel 7  -->
				<div class="card carousel-cell border-0 rounded my-3">
					<img src="<?= base_url('assets/images/vacation-packages/vacation-3.png') ?>" class="card-img" width="100%" height="100%" alt="Vacation Image">
					<div class="img-overlay">
						<div class="position-absolute bottom-0 start-0 mx-2">
							<h4 class="fw-bold text-white">Accra</h4>
							<p class="fw-semibold text-white">156 Things to do</p>
						</div>
					</div>
					<a href="#" class="stretched-link"></a>
				</div>
				<!-- carousel 7 -->

				<!-- carousel 8  -->
				<div class="card carousel-cell border-0 rounded my-3">
					<img src="<?= base_url('assets/images/vacation-packages/vacation-1.png') ?>" class="card-img" width="100%" height="100%" alt="Vacation Image">
					<div class="img-overlay">
						<div class="position-absolute bottom-0 start-0 mx-2">
							<h4 class="fw-bold text-white">Accra</h4>
							<p class="fw-semibold text-white">156 Things to do</p>
						</div>
					</div>
					<a href="#" class="stretched-link"></a>
				</div>
				<!-- carousel 8 -->
			</div>
			<!-- vacation-package slider -->
		</div>
	</div>
</section>
<!-- Vacation -->
