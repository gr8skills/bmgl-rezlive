<!-- Web timeline -->
<section class="local-flight-timeline d-none d-lg-block" data-aos="fade-up" data-aos-duration="1000">
	<div class="container">
		<div class="d-flex align-items-center">
			<div class="number-outline completed">
				<span>1</span>
				<i class="ri-check-line ri-lg" style="color: #fff;"></i>
			</div>
			<p class="fw-bold">Your selection</p>
			<img src="<?= base_url('assets/images/flight-deals/line.svg') ?>" width="auto" height="2" alt="">
		</div>
		<div class="d-flex align-items-center">
			<div class="number-outline completed">
				<span>2</span>
				<i class="ri-check-line ri-lg" style="color: #fff;"></i>
			</div>
			<p class="fw-bold">Your details </p>
			<img src="<?= base_url('assets/images/flight-deals/line.svg') ?>" width="auto" height="2" alt="">
		</div>
		<div class="d-flex align-items-center">
			<div class="number-outline">
				<span>3</span>
				<i class="fa fa-check fw-bold" style="color: #fff;"></i>
			</div>
			<p class="fw-bold">Final step</p>
		</div>
	</div>
</section>
<!-- Web timeline -->

<!-- mobile timeline -->
<!-- <section class="d-lg-none">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="horizontal timeline">
					<div class="steps">
						<div class="step">
						</div>
						<div class="step completed">
						</div>
						<div class="step">
						</div>
					</div>
					<div class="line completed"></div>
				</div>
			</div>
		</div>
	</div>
</section> -->
<!-- mobile timeline -->


<section class="hotel-bookings pb-5">
	<div class="container">
		<div class="row g-3">
			<div class="col-lg-12 d-lg-none" data-aos="fade-up" data-aos-duration="1000">
				<a href="#">
					<img src="<?= base_url('assets/images/icons/arrow-left-line.svg') ?>" alt="">
				</a>
			</div>
			<div class="col-lg-4 order-lg-1 order-2">
				<!--  -->
				<div class="card border-0" data-aos="fade-up" data-aos-duration="1000">
					<div class="card-body">
						<h5 class="fw-bold">Your booking details</h5>
						<div class="row mt-4">
							<?php
							$arrivalDate = DateTime::createFromFormat('d/m/Y', $apiResponse ? $apiResponse->arrivalDate : $default->arrivalDate);
							$departureDate = DateTime::createFromFormat('d/m/Y', $apiResponse ? $apiResponse->departureDate : $default->departureDate);
							if ($departureDate <= $arrivalDate) {
								$nights = 0; // or throw an error
							} else {
								$nights = $arrivalDate->diff($departureDate)->days;
							}
							$nights > 1 ? $nightText = 'Nights' : $nightText = 'Night';
							$rate = 1;
							$currency = '&#8358;';
							if ($apiResponse && $apiResponse->currency == 'USD' && $apiResponse->countryCode == 'NG') {
								// convert to NGN
								$url = "https://open.er-api.com/v6/latest/USD";
								$response = file_get_contents($url);

								if ($response === FALSE) {
									die("Error fetching exchange rate.");
								}

								$data = json_decode($response, true);

								if (isset($data["rates"]["NGN"])) {
									$rate = $data["rates"]["NGN"];
								}
							} else {
								$currency = $apiResponse?$apiResponse->currency:$default->currency;
							}
							$tax = ($apiResponse?$apiResponse->totalRate:$default->totalRate)*0.05*$rate;
							$cityTax = ($apiResponse?$apiResponse->totalRate:$default->totalRate)*0.02*$rate;
							$total = ($apiResponse?$apiResponse->totalRate:$default->totalRate)*$rate + $tax + $cityTax;
							?>
							<div class="col-lg-6 col-6 border-end">
								<p class="small">Check-in</p>
								<h6 class="fw-bold"><?= $arrivalDate->format('D, d M Y'); ?></h6>
								<p class="small">From 12:00 AM</p>
							</div>
							<div class="col-lg-6 col-6">
								<p class="small">Check-out</p>
								<h6 class="fw-bold"><?= $departureDate->format('D, d M Y'); ?></h6>
								<p class="small">From 12:00 AM</p>
							</div>
							<div class="col-lg-12 mt-3">
								<p class="small">Total length of stay</p>
								<h5 class="fw-bold"><?= $nights.' '. $nightText ?></h5>
							</div>
							<hr class="mt-2">

							<div class="col-lg-12 mt-3">
								<p class="small fw-bold">You selected:</p>
								<h5 class="fw-bold"><?= $apiResponse?$apiResponse->roomType : $default->roomType ?></h5>
								<a href="#" class="text-decoration-none fw-bold small">Change your selection</a>
							</div>
						</div>
					</div>
				</div>
				<!--  -->

				<!--  -->
				<div class="card border-0 mt-3" data-aos="fade-up" data-aos-duration="1000">
					<div class="card-body">
						<h5 class="fw-bold">Payment Summary</h5>
						<div class="row mt-4">
							<div class="col-6">
								<p class="fw-bold mb-n1">Price:</p>
								<p class="small">Hotel’s Currency: <?= $apiResponse?$apiResponse->currency:$default->currency.' '.number_format($apiResponse?$apiResponse->totalRate : $default->totalRate, 2) ?></p>
							</div>
							<div class="col-6 text-lg-end">
								<p class="text-success fw-bold"><?= $currency.' '.number_format($apiResponse?$apiResponse->totalRate : $default->totalRate, 2) ?></p>
							</div>
						</div>

						<div class="row">
							<div class="col-6">
								<p class="fw-bold">TAX</p>
								<p class="fw-bold">City tax</p>
							</div>
							<div class="col-6 text-lg-end">
								<p class="text-success fw-bold"><?= $currency.' '.number_format($tax, 2) ?></p>
								<p class="text-success fw-bold"><?= $currency.' '.number_format($cityTax, 2) ?></p>
							</div>
						</div>
						<hr />

						<div class="row mt-3">
							<div class="col-6">
								<h5 class="fw-bold my-auto">Total</h5>
							</div>
							<div class="col-6 text-lg-end">
								<h5 class="text-success fw-bold my-auto"><?= $currency.' '.number_format($total, 2) ?></h5>
							</div>
						</div>
						<hr />

						<div class="col-lg-12">
							<p class="small">* This price is converted to show you the approximate cost in NGN.
								You'll pay in US$.
								The exchange rate might change before you pay.
							</p>
							<p class="small">
								Keep in mind that your card issuer may charge you a foreign transaction fee.</p>
						</div>
						<hr />

						<div class="col-lg-12">
							<h6 class="fw-bold">Your payment schedule</h6>
							<p class="small">Before check-in you’ll pay <span
									class="text-success fw-bold"><?= $currency.' '.number_format($total, 2) ?></span></p>
						</div>
					</div>
				</div>
				<!--  -->

				<!--  -->
				<div class="card border-0 mt-3" data-aos="fade-up" data-aos-duration="1000">
					<div class="card-body">
						<h6 class="fw-bold">The fine print</h6>
						<p class="small">You must show a valid photo ID and credit card upon check-in. Please note
							that all
							special requests cannot be guaranteed and are subject to availability upon check-in.
							Additional charges may apply.
						</p>
						<p class="small">
							Due to the coronavirus (COVID-19), wearing a face mask is mandatory in all indoor common
							areas.
						</p>
						<p class="small">
							Guests are required to show a photo ID and credit card upon check-in. Please note that
							all
							Special Requests are subject to availability and additional charges may apply.
						</p>
						<p class="small">
							Swimming pool #1: Closed from Mon, Sept 06, 2021 until Thu, May 26, 2022</p>
					</div>
				</div>
				<!--  -->
			</div>
			<div class="col-lg-8 order-lg-1 order-1">
				<!--  -->
				<div class="card border-0">
					<div class="card-body">
						<div class="row g-3" data-aos="fade-up" data-aos-duration="1000">
							<div class="col-lg-4" data-aos="fade-up" data-aos-duration="1000">
								<img src="<?= base_url('assets/images/hotel/hotel-1.png') ?>" width="100%" height="auto"
									 class="img-fluid" alt="">
							</div>
							<div class="col-lg-8" data-aos="fade-up" data-aos-duration="1000">
								<div class="row g-3">
									<div class="col-lg-12">
										<div class="row">
											<div class="col-lg-8 col-6">
												<h5 class="fw-bold mb-n0"><?= $apiResponse?$apiResponse->hotelName : $default->hotelName ?></h5>
												<img src="<?= base_url('assets/images/hotel/rating.svg') ?>" class="img-fluid" alt="">
											</div>

											<div class="col-lg-4 col-6 d-flex">
												<div class="col-8">
													<h6 class="fw-bold">Very Good</h6>
													<p class="small"><?= mt_rand(2550, 4980); ?> reviews</p>
												</div>
												<div class="col-4">
													<span class="badge bg-warning p-3">7.8</span>
												</div>
											</div>
										</div>

										<div class="row">
											<div class="mt-2">
												<a href="#"
												   class="me-1 text-decoration-underline"><?= $default->cityName ?></a> &bull; <a
													href="https://www.google.com/maps/@<?= $hotelDetails->Latitude . ',' . $hotelDetails->Longitude . ',15z' ?>" class="mx-1 text-decoration-underline" target="_blank">Show on
													map</a>
												&bull;
												<span class="small mx-1">1km from center</span>
											</div>
										</div>

										<div class="row mt-lg-0 mt-3 align-items-end">
											<div class="col-lg-4 lh-1">
												<p class="text-danger">Non-refundable</p>
												<p>Max people: <b>2</b></p>
											</div>
											<div class="col-lg-8 text-lg-end lh-1">
												<?php
												$roomType = explode(',', $default->roomType);
												?>
												<p class="fw-bold"><?= $roomType[0] ?></p>
												<p class="fw-bold"><?= $roomType[1] ?></p>
												<p class="text-success fw-bold"><?= $default->boardBasis ?></p>
												<p class="text-success fw-bold">Free cancellation. No payment needed
												</p>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!--  -->

				<!--  -->
				<div class="bg-secondary mt-5 p-4">
					<div class="col-lg-12">
						<h5 class="fw-bold" data-aos="fade-up" data-aos-duration="1000">Enter your details</h5>

						<form action="" data-aos="fade-up" data-aos-duration="1000">
							<div class="row">
								<div class="col">
									<label for="inputFirstName" class="form-label fw-bold">First Name <sup
											class="text-danger">*</sup></label>
									<input type="text" class="form-control rounded-0 border-0"
										   aria-label="First name">
								</div>
								<div class="col">
									<label for="inputLastName" class="form-label fw-bold">Last Name <sup
											class="text-danger">*</sup></label>
									<input type="text" class="form-control rounded-0 border-0"
										   aria-label="Last name">
								</div>
							</div>

							<div class="row mt-4">
								<div class="col">
									<label for="inputEmail" class="form-label fw-bold">Email</label>
									<input type="email" class="form-control rounded-0 border-0" aria-label="Email">
								</div>
								<div class="col">
									<label for="inputConfirmEmail" class="form-label fw-bold">Confirm Email</label>
									<input type="text" class="form-control rounded-0 border-0"
										   aria-label="Confirm Email">
								</div>
							</div>

							<div class="row mt-3">
								<p class="fw-bold">Who are you booking for?</p>
							</div>

							<div class="form-check">
								<input class="form-check-input" type="radio" name="flexRadioDefault"
									   id="flexRadioDefault1" checked>
								<label class="form-check-label small" for="flexRadioDefault1">
									I am the main guest
								</label>
							</div>

							<div class="form-check">
								<input class="form-check-input" type="radio" name="flexRadioDefault"
									   id="flexRadioDefault1">
								<label class="form-check-label small" for="flexRadioDefault1">
									I am booking for someone else
								</label>
							</div>
						</form>
					</div>
				</div>
				<!--  -->

				<!--  -->
				<div class="bg-secondary mt-5 p-4" data-aos="fade-up" data-aos-duration="1000">
					<div class="col-lg-12">
						<h5 class="fw-bold">special request</h5>
						<p class="small">Special requests can't be guaranteed, but the property will do its best to
							meet your needs. You can always make a special request after your booking is complete
						</p>

						<form action="">
							<div class="col">
                                    <textarea class="form-control rounded-0 border-0" id="exampleFormControlTextarea1"
											  rows="5"></textarea>
							</div>
						</form>
					</div>
				</div>
				<!--  -->

				<div class="row" data-aos="fade-up" data-aos-duration="1000">
					<div class="col-lg-6 mt-5">
						<h5 class="fw-bold">Your arrival time</h5>
						<p class="small"><i class="ri-checkbox-circle-line ri-lg text-success"></i> Your room will
							be
							ready
							for check-in by 12:00 AM</p>
						<p class="small"><i class="ri-24-hours-line ri-lg text-success"></i> 24 hour
							desk help whenever you need</p>
					</div>

					<div class="col-lg-6 mt-5">
						<h6><b>Add your estimated arrival time</b> (optimal)</h6>
						<div class="col-lg-5">
							<select class="form-select" aria-label="Default select example">
								<option selected>Please select</option>
								<option value="1">One</option>
								<option value="2">Two</option>
								<option value="3">Three</option>
							</select>
						</div>
					</div>
				</div>



				<div class="col-lg-12 mt-3" data-aos="fade-up" data-aos-duration="1000">
					<div class="d-grid d-md-flex justify-content-md-end">
						<a href="hotel-4.html" class="btn btn-warning fw-bold btn-lg px-5">Pay <?= $currency.' '.$total ?> Now</a>
					</div>
				</div>

			</div>
		</div>
	</div>
</section>
