<?php
/**
 * Convert USD price to NGN (Nigerian Naira)
 * @param float|string $price Price in USD
 * @param string $sourceCurrency Source currency code (default USD)
 * @return float Price in NGN
 */
function convertToNaira($price, $sourceCurrency = 'USD') {
	$price = (float) $price;
	// Only convert if source is USD
	if (strtoupper($sourceCurrency) === 'USD') {
		return $price * USD_TO_NGN_RATE;
	}
	return $price;
}

// Get API currency for conversion
$apiCurrency = isset($apiResponse->Currency) ? (string)$apiResponse->Currency : 'USD';
?>

<section class="hotel-bookings">
	<div class="container">
		<div class="row g-3">
			<div class="col-lg-12 d-lg-none">
				<a href="#" onclick="document.getElementById('pricesSection').scrollIntoView({ behavior: 'smooth' });">
					<img src="<?= base_url('assets/images/icons/arrow-left-line.svg') ?>" alt="">
				</a>
			</div>
			<div class="col-lg-4 d-none d-lg-block" data-aos="fade-up" data-aos-duration="1000">
				<div class="card border-0">
					<div class="card-header bg-warning text-primary border-0">
						<h5 class="fw-bold my-auto">Search</h5>
					</div>
					<div class="card-body">
						<form action="<?php echo site_url('home/search')?>" method="post">
							<input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>"
								   value="<?= $this->security->get_csrf_hash(); ?>" />
							<div class="input-group mb-3">
                                    <span class="input-group-text bg-transparent border border-end-0"><i
											class="ri-search-line ri-lg"></i></span>
								<style>
									/* Small loader inside input */
									#searchBox.loading {
										background: url('https://i.imgur.com/6RMhx.gif') no-repeat right center;
										background-size: 18px 18px;
										color: #ffa62300;
									}
								</style>
								<input name="searchBox" type="text" class="form-control form-control-lg border border-start-0"
									   id="searchBox" placeholder="Search">

								<script>
									$(document).ready(function () {
										$("#searchBox").autocomplete({
											source: function(request, response) {
												$.ajax({
													url: "<?= site_url('home/autocomplete'); ?>",
													type: "GET",
													dataType: "json",
													data: {
														term: request.term,
														"<?= $this->security->get_csrf_token_name(); ?>": "<?= $this->security->get_csrf_hash(); ?>"
													},
													beforeSend: function() {
														// add loader class before request
														$("#searchBox").addClass("loading");
													},
													success: function(data) {
														response(data);
													},
													complete: function() {
														// remove loader class after request
														$("#searchBox").removeClass("loading");
													}
												});
											},
											minLength: 3, // start after 2 characters
											search: function() {
												$(this).addClass("loading");
											},
											response: function() {
												$(this).removeClass("loading");
											},
											select: function(event, ui) {
												$("#searchBox").val(ui.item.value);
											}
										});
									});
								</script>

							</div>
							<?php
							// Convert dd/mm/YYYY → YYYY-MM-DD
							$arrival = DateTime::createFromFormat('Y-m-d', (string)$defaults->arrival);
							$departure = DateTime::createFromFormat('Y-m-d', (string)$defaults->departure);
							$arrivalFormatted = $arrival ? $arrival->format('Y-m-d') : '';
							$departureFormatted = $departure ? $departure->format('Y-m-d') : '';
							?>

							<div class="mb-3">
								<label for="checkIn" class="form-label fw-bold">Check-in Date</label>
								<input type="date" value="<?= $arrivalFormatted ?>" class="form-control form-control-lg" id="checkIn" name="checkIn">
							</div>

							<div class="mb-3">
								<label for="checkOut" class="form-label fw-bold">Check-out Date</label>
								<input type="date" value="<?= $departureFormatted?>" class="form-control form-control-lg" id="checkOut" name="checkOut">
							</div>

							<div class="mb-3">
								<select class="form-select form-select-lg" aria-label="Default select example" name="guests">
									<option value="1,0,1" <?php echo ($defaults->guests == "1,0,1") ? 'selected' : ''; ?>>1 Adult, 0 Children, 1 Room</option>
									<option value="1,1,1" <?php echo ($defaults->guests == "1,1,1") ? 'selected' : ''; ?>>1 Adult, 1 Child, 1 Room</option>
									<option value="1,2,1" <?php echo ($defaults->guests == "1,2,1") ? 'selected' : ''; ?>>1 Adult, 2 Children, 1 Room</option>
									<option value="1,3,1" <?php echo ($defaults->guests == "1,3,1") ? 'selected' : ''; ?>>1 Adult, 3 Children, 1 Room</option>
									<option value="1,4,1" <?php echo ($defaults->guests == "1,4,1") ? 'selected' : ''; ?>>1 Adult, 4 Children, 1 Room</option>
									<option value="1,2,2" <?php echo ($defaults->guests == "1,2,2") ? 'selected' : ''; ?>>1 Adult, 2 Children, 2 Rooms</option>
									<option value="1,3,2" <?php echo ($defaults->guests == "1,3,2") ? 'selected' : ''; ?>>1 Adult, 3 Children, 2 Rooms</option>
									<option value="1,4,2" <?php echo ($defaults->guests == "1,4,2") ? 'selected' : ''; ?>>1 Adult, 4 Children, 2 Rooms</option>
									<option value="2,0,1" <?php echo ($defaults->guests == "2,0,1") ? 'selected' : ''; ?>>2 Adults, 0 Children, 1 Room</option>
									<option value="2,0,2" <?php echo ($defaults->guests == "2,0,2") ? 'selected' : ''; ?>>2 Adults, 0 Children, 2 Rooms</option>
									<option value="2,1,1" <?php echo ($defaults->guests == "2,1,1") ? 'selected' : ''; ?>>2 Adults, 1 Child, 1 Room</option>
									<option value="2,1,2" <?php echo ($defaults->guests == "2,1,2") ? 'selected' : ''; ?>>2 Adults, 1 Child, 2 Rooms</option>
									<option value="2,2,2" <?php echo ($defaults->guests == "2,2,2") ? 'selected' : ''; ?>>2 Adults, 2 Children, 2 Rooms</option>
									<option value="2,3,2" <?php echo ($defaults->guests == "2,3,2") ? 'selected' : ''; ?>>2 Adults, 3 Children, 2 Rooms</option>
									<option value="2,4,2" <?php echo ($defaults->guests == "2,4,2") ? 'selected' : ''; ?>>2 Adults, 4 Children, 2 Rooms</option>
								</select>
							</div>

							<div class="d-grid">
								<button type="submit" class="btn btn-primary btn-lg fw-bold">Search</button>
							</div>
						</form>
					</div>
				</div>
				<div class="col-lg-12 mt-4 mb-4" data-aos="fade-up" data-aos-duration="1000">
					<div class="map">
						<img src="<?= base_url('assets/images/hotels-and-location/map-2.png') ?>" width="100%" height="auto"
							 class="img-fluid" alt="">
					</div>
				</div>
			</div>

			<div class="col-lg-8">
				<!-- web -->
				<div class="d-none d-lg-block" data-aos="fade-up" data-aos-duration="1000">
					<div class="row">
						<div class="col">
							<button type="button" class="btn white-btn active" onclick="document.getElementById('pricesSection').scrollIntoView({ behavior: 'smooth' });">Info & prices</button>
						</div>
						<div class="col">
							<button type="button" class="btn white-btn" onclick="document.getElementById('facilitiesSection').scrollIntoView({ behavior: 'smooth' });">Facilities</button>
						</div>
						<div class="col">
							<button type="button" class="btn white-btn" onclick="document.getElementById('hotelRulesSection').scrollIntoView({ behavior: 'smooth' });">Hotel rules</button>
						</div>
						<div class="col">
							<button type="button" class="btn white-btn" onclick="document.getElementById('theFinePrintSection').scrollIntoView({ behavior: 'smooth' });">The fine print</button>
						</div>
						<div class="col">
							<button type="button" class="btn white-btn" onclick="document.getElementById('guestReviewsSection').scrollIntoView({ behavior: 'smooth' });">Guest reviews</button>
						</div>
					</div>
				</div>
				<!-- web -->

				<!-- mobile -->
				<div class="d-lg-none" data-aos="fade-up" data-aos-duration="1000">
					<div class="main-gallery js-flickity"
						 data-flickity-options='{ "cellAlign": "left", "contain": "true", "freeScroll": "true", "prevNextButtons": false }'>
						<div class="carousel-cell">
							<button type="button" class="btn white-btn active"
									onclick="document.getElementById('pricesSection').scrollIntoView({ behavior: 'smooth' });">Info & prices</button>
						</div>
						<div class="carousel-cell">
							<button type="button" class="btn white-btn"
									onclick="document.getElementById('facilitiesSection').scrollIntoView({ behavior: 'smooth' });">Facilities</button>
						</div>
						<div class="carousel-cell">
							<button type="button" class="btn white-btn"
									onclick="document.getElementById('hotelRulesSection').scrollIntoView({ behavior: 'smooth' });">Hotel rules</button>
						</div>
						<div class="carousel-cell">
							<button type="button" class="btn white-btn"
									onclick="document.getElementById('theFinePrintSection').scrollIntoView({ behavior: 'smooth' });">The fine print</button>
						</div>
						<div class="carousel-cell">
							<button type="button" class="btn white-btn"
									onclick="document.getElementById('guestReviewsSection').scrollIntoView({ behavior: 'smooth' });">Guest reviews</button>
						</div>
					</div>
				</div>
				<!-- mobile -->

				<div class="row mt-4" data-aos="fade-up" data-aos-duration="1000">
					<div class="col-lg-9 col-8">
						<h5 class="fw-bold mb-n0"><?= $defaults->hotelName ?></h5>
						<img src="<?= base_url('assets/images/hotel/rating.svg') ?>" class="img-fluid" alt="">
					</div>
					<div class="col-lg-3 col-4 d-flex text-end">
						<div class="col">
							<span><i class="ri-heart-line ri-2x"></i></span>
						</div>
						<div class="col">
							<span><i class="ri-share-fill ri-2x"></i></span>
						</div>
						<div class="col-7 d-grid d-none d-lg-block">
							<a href="#" class="btn btn-primary" onclick="document.getElementById('pricesSection').scrollIntoView({ behavior: 'smooth' });">Reserve</a>
						</div>
					</div>
				</div>
				<div class="col-md-12 d-none d-lg-block mt-3" data-aos="fade-up" data-aos-duration="1000">
					<p><i class="ri-map-pin-line ri-lg"></i> <?= $hotelDetails->HotelAddress . ', ' . $hotelDetails->City . ' ' . $hotelDetails->Country ?></p>
					<a href="https://www.google.com/maps/@<?= $hotelDetails->Latitude . ',' . $hotelDetails->Longitude . ',15z' ?>" class="small" target="_blank">Show on map</a>
				</div>
				<!-- web -->
				<div class="d-none d-lg-block">
					<?php
					// Get hotel images from API or use defaults
					$hotelImages = [];
					if (isset($hotelDetails->Images) && !empty($hotelDetails->Images)) {
						$hotelImages = is_array($hotelDetails->Images) ? $hotelDetails->Images : [$hotelDetails->Images];
					}
					$defaultImg = base_url('assets/images/hotel/hotel-5.png');
					?>
					<div class="row mt-3 g-2" data-aos="fade-up" data-aos-duration="1000">
						<div class="col-lg-4">
							<div class="col">
								<img src="<?= isset($hotelImages[0]) ? $hotelImages[0] : $defaultImg ?>" width="100%" height="200" alt="<?= htmlspecialchars($defaults->hotelName) ?>">
							</div>
							<div class="col mt-2">
								<img src="<?= isset($hotelImages[1]) ? $hotelImages[1] : $defaultImg ?>" width="100%" height="200" alt="<?= htmlspecialchars($defaults->hotelName) ?>">
							</div>
						</div>
						<div class="col-lg-8">
							<img src="<?= isset($hotelImages[2]) ? $hotelImages[2] : $defaultImg ?>" width="100%" height="100%" alt="<?= htmlspecialchars($defaults->hotelName) ?>">
						</div>
					</div>

					<div class="row mt-1 g-2" data-aos="fade-up" data-aos-duration="1000">
						<div class="col-lg-3">
							<img src="<?= isset($hotelImages[3]) ? $hotelImages[3] : $defaultImg ?>" width="100%" height="150" alt="<?= htmlspecialchars($defaults->hotelName) ?>">
						</div>
						<div class="col-lg-3">
							<img src="<?= isset($hotelImages[4]) ? $hotelImages[4] : $defaultImg ?>" width="100%" height="150" alt="<?= htmlspecialchars($defaults->hotelName) ?>">
						</div>
						<div class="col-lg-3">
							<img src="<?= isset($hotelImages[5]) ? $hotelImages[5] : $defaultImg ?>" width="100%" height="150" alt="<?= htmlspecialchars($defaults->hotelName) ?>">
						</div>
						<div class="col-lg-3">
							<img src="<?= isset($hotelImages[6]) ? $hotelImages[6] : $defaultImg ?>" width="100%" height="150" alt="<?= htmlspecialchars($defaults->hotelName) ?>">
						</div>
					</div>
				</div>

				<!-- web -->

				<!-- mobile hotel carousel -->
				<div class="row mt-5 d-lg-none" data-aos="fade-up" data-aos-duration="1000">
					<div class="main-gallery js-flickity hotel-mobile-carousel"
						 data-flickity-options='{ "cellAlign": "center", "contain": "true", "freeScroll": "true", "wrapAround": true }'>
						<?php
						// Mobile carousel - use hotel images from API or defaults
						$carouselCount = !empty($hotelImages) ? min(count($hotelImages), 8) : 4;
						for ($i = 0; $i < $carouselCount; $i++):
							$imgSrc = isset($hotelImages[$i]) ? $hotelImages[$i] : $defaultImg;
						?>
						<div class="card carousel-cell border-0 rounded my-3">
							<img src="<?= $imgSrc ?>" class="card-img" width="100%" height="auto"
								 alt="<?= htmlspecialchars($defaults->hotelName) ?> - Image <?= $i + 1 ?>">
						</div>
						<?php endfor; ?>
					</div>
					<div class="col-lg-12 text-center">
						<a href="<?= isset($hotelDetails->Latitude) && isset($hotelDetails->Longitude) ? 'https://www.google.com/maps/@' . $hotelDetails->Latitude . ',' . $hotelDetails->Longitude . ',15z' : '#' ?>" target="_blank">
							<i class="ri-map-pin-line ri-lg"></i>
							<?= isset($hotelDetails->HotelAddress) ? htmlspecialchars($hotelDetails->HotelAddress) : '' ?>
							<?= isset($hotelDetails->City) ? ', ' . htmlspecialchars($hotelDetails->City) : '' ?>
							<?= isset($hotelDetails->Country) ? ' ' . htmlspecialchars($hotelDetails->Country) : '' ?>
						</a>

						<div class="col-6 d-grid mt-3 m-auto">
							<a href="#" onclick="document.getElementById('pricesSection').scrollIntoView({ behavior: 'smooth' });" class="btn btn-primary btn-lg fw-bold">Reserve</a>
						</div>
					</div>
				</div>

				<!-- mobile hotel carousel -->
			</div>
		</div>
	</div>
</section>

<section>
	<div class="container">
		<div class="row text-center justify-content-between" data-aos="fade-up" data-aos-duration="1000">
			<div class="col-lg col-4">
				<i class="ri-bear-smile-line ri-2x"></i>
				<p class="fw-bold">Pet friendly</p>
			</div>
			<div class="col-lg col-md-4 col">
				<i class="ri-wifi-line ri-2x"></i>
				<p class="fw-bold">Free Wi-fi</p>
			</div>
			<div class="col-lg col-md-4 col">
				<i class="ri-parking-line ri-2x"></i>
				<p class="fw-bold">Free Parking</p>
			</div>
			<div class="col-lg col-md-4 col-5">
				<i class="ri-celsius-line ri-2x"></i>
				<p class="fw-bold">Air-conditioning</p>
			</div>
			<div class="col-lg col-md-4 col-4">
				<i class="ri-cup-line ri-2x"></i>
				<p class="fw-bold">Free breakfast</p>
			</div>
			<div class="col-lg col-md-4 col">
				<i class="ri-fire-line ri-2x"></i>
				<p class="fw-bold">Heating</p>
			</div>
			<div class="col-lg col-md-4 col-5">
				<i class="ri-24-hours-line ri-2x"></i>
				<p class="fw-bold">24-Hour front desk</p>
			</div>
		</div>

		<div class="row mt-5">
			<div class="col-lg-8" data-aos="fade-up" data-aos-duration="1000">
				<p>
					<?= !empty($hotelDetails->Description) ? $hotelDetails->Description : "Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown
					printer took a galley of type and scrambled it to make a type specimen book. It has survived not
					only five centuries, but also the leap into electronic typesetting, remaining essentially
					unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem
					Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown
					printer took a galley of type and scrambled it to make a type specimen book. It has survived not
					only five centuries, but also the leap into electronic typesetting, remaining essentially
					unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem
					Lorem Ipsum has been the industry's standard dummy text ever since" ?>
					</p>
			</div>
			<div class="col-lg-4">
				<div class="card border-0" data-aos="fade-up" data-aos-duration="1000">
					<div class="card-header bg-warning border-0">
						<span class="fw-bold">Property Highlights</span>
					</div>
					<div class="card-body">
						<h6 class="fw-bold mb-3">Perfect for a 1-night stay!</h6>

						<div class="row">
							<div class="col-1">
								<p><i class="ri-map-pin-line ri-lg"></i></p>
							</div>
							<div class="col-11">
								<p class="small">Top Location: Highly rated by
									recent guests (8.5)
									Popular with solo travelers</p>
							</div>
						</div>

						<div class="row">
							<div class="col-1">
								<p><i class="ri-cup-line"></i></p>
							</div>
							<div class="col-11">
								<p class="small">Continental breakfast</p>
							</div>
						</div>

						<div class="row">
							<div class="col-1">
								<p><i class="ri-parking-line"></i></p>
							</div>
							<div class="col-11">
								<p class="small">Free parking available at the hotel</p>
							</div>
						</div>

						<div class="d-grid">
							<a href="#" onclick="document.getElementById('pricesSection').scrollIntoView({ behavior: 'smooth' });" class="btn btn-primary fw-bold">Reserve</a>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="row mt-4" data-aos="fade-up" data-aos-duration="1000">
			<div class="col-lg-4">
				<h4 class="fw-bold">Availability</h4>
			</div>
			<div class="col-lg-8 d-lg-flex align-items-center ">
				<?php
				$arrivalDate = DateTime::createFromFormat('Y-m-d', (string)$defaults->arrival);
				$departureDate = DateTime::createFromFormat('Y-m-d', (string)$defaults->departure);

				if ($arrivalDate && $departureDate) {
					$formatted = $arrivalDate->format('D, M j') . ' - ' . $departureDate->format('D, M j');
				}
				?>
				<div class="col-lg-4 col-md-12 border text-center p-2">
					<span><i class="ri-calendar-line ri-lg"></i> <?= $formatted ?></span>
				</div>
				<div class="col-lg-4 col-md-12 border text-center p-2">
					<span><i class="ri-user-line ri-lg"></i> <?= $defaults->adults ?? 1 ?> Adult, <?= $defaults->children ?? 0 ?>  Children, <?= $defaults->rooms ?? 1 ?> Room</span>
				</div>
				<div class="col-lg-4 col-sm-12 d-grid">
					<button type="button" class="btn btn-primary rounded-0 p-2">Search</button>
				</div>
			</div>
		</div>


		<div class="col-lg-12 mt-5 p-2 bg-warning text-primary fw-bold text-center d-none d-lg-block"
			 data-aos="fade-up" data-aos-duration="1000" id="pricesSection">
			<div class="row">
				<div class="col-lg-3">
					<h6 class="fw-bold my-auto">Room Type</h6>
				</div>
				<div class="col-lg-2">
					<h6 class="fw-bold my-auto">Sleeps</h6>
				</div>
				<div class="col-lg-2">
					<h6 class="fw-bold my-auto">Today's Prices</h6>
				</div>
				<div class="col-lg">
					<h6 class="fw-bold my-auto">Your Choices</h6>
				</div>
				<div class="col-lg">
					<h6 class="fw-bold my-auto text-start">Rooms</h6>
				</div>
			</div>
		</div>

		<!-- web -->
		<div class="col-lg-12 mt-3 d-none d-lg-block">
			<!--  -->
			<?php if (!empty($roomDetails)) {?>
				<?php foreach ($roomDetails as $detail) {?>
					<div class="row p-3" data-aos="fade-up" data-aos-duration="1000">
						<div class="col-lg-3">
							<?php
								$roomType = explode(',', $detail->Type);
							?>
							<h6 class="fw-bold"><?= $roomType[0] ?></h6>
							<p><i class="ri-hotel-bed-line ri-2x"></i> <?= $roomType[1] ?></p>
							<p class="small">35m <sup>2</sup> , <?= $detail->RoomDescription ?></p>
							<hr />
							<div class="col d-flex gap-2 flex-wrap">
									<span class="small"><i class="ri-check-line ri-lg text-success"></i> Free
										toiletries</span>
								<span class="small"><i class="ri-check-line ri-lg text-success"></i> Desk</span>
								<span class="small"><i class="ri-check-line ri-lg text-success"></i> TV</span>
								<span class="small"><i class="ri-check-line ri-lg text-success"></i> Refrigerators</span>
								<span class="small"><i class="ri-check-line ri-lg text-success"></i> Ironing
										facilities</span>
								<span class="small"><i class="ri-check-line ri-lg text-success"></i> Microwave</span>
								<span class="small"><i class="ri-check-line ri-lg text-success"></i> Coffee/Tea maker</span>
								<span class="small"><i class="ri-check-line ri-lg text-success"></i> Hair drier</span>

								<span class="small"><i class="ri-check-line ri-lg text-success"></i> Cable channels</span>
							</div>

							<div class="col mt-3">
								<a href="#" class="text-success small text-decoration-underline">View Pictures</a>
							</div>
						</div>
						<div class="col-lg-2 text-center">
							<p><i class="ri-user-fill"></i> <i class="ri-user-fill"></i></p>
						</div>
						<div class="col-lg-2">
							<h5 class="fw-bold text-success"><?= DISPLAY_CURRENCY_SYMBOL ?> <?= number_format(convertToNaira($detail->TotalRate, $apiCurrency), 2) ?></h5>
							<?php $taxAmount = convertToNaira($detail->TotalRate, $apiCurrency) * 0.05; ?>
							<p class="small">+ <?= DISPLAY_CURRENCY_SYMBOL ?> <?= number_format($taxAmount, 2) ?> taxes and charges</p>
						</div>
						<div class="col-lg">
							<div class="row g-0">
								<div class="col-1">
									<p class="small text-success"><i class="ri-cup-line ri-lg"></i></p>
								</div>
								<div class="col-10">
									<p class="small text-success"> <?= $detail->BoardBasis ?>
										included</p>
								</div>
								<div class="col-1">
									<a href="#"><i class="ri-question-line"></i></a>
								</div>
							</div>

							<div class="row g-0">
								<div class="col-1">
									<p class="small text-success"><i class="ri-check-line ri-lg"></i></p>
								</div>
								<div class="col-11">
									<p class="small text-success"><span class="fw-bold"><?= $detail->CancellationPolicy->Refundable ?>
												cancellation</span> until 11:49 PM
										on <?= $departureDate->format('F j, Y') ?>.</p>
								</div>
							</div>

							<div class="row g-0">
								<div class="col-1">
									<p class="small text-success"><i class="ri-check-line ri-lg"></i></p>
								</div>
								<div class="col-11">
									<p class="small text-success"><span class="fw-bold">NO PAYMENT NEEDED -</span> pay at
										the hotel</p>
								</div>
							</div>

							<div class="row g-0">
								<div class="col-1">
									<p><i class="ri-price-tag-3-line ri-lg"></i></p>
								</div>
								<div class="col-11">
									<p class="small"> Discount available</p>
								</div>
							</div>
						</div>
						<div class="col-lg text-start">
							<div class="row">
								<div class="col-4">
									<div class="btn-group btn-group-sm" role="group" aria-label="Small button group">
										<button type="button" class="btn btn-outline-dark rounded-0">-</button>
										<button type="button" class="btn btn-outline-dark border-0">0</button>
										<button type="button" class="btn btn-outline-dark rounded-0">+</button>
									</div>
								</div>
								<div class="col-8 d-grid">
									<form action="<?php echo site_url('booking/index')  ?>" method="post">
										<input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
										<input type="hidden" name="searchSessionId" value="<?= $defaults->searchSessionId ?>">
										<input type="hidden" name="arrivalDate" value="<?= $defaults->arrival ?>">
										<input type="hidden" name="departureDate" value="<?= $defaults->departure ?>">
										<input type="hidden" name="countryCode" value="<?= $defaults->countryCode ?>">
										<input type="hidden" name="cityCode" value="<?= $hotelDetails->CityCode ?>">
										<input type="hidden" name="hotelId" value="<?= $hotelDetails->HotelId ?>">
										<input type="hidden" name="hotelName" value="<?= $hotelDetails->HotelName ?? '' ?>">
										<input type="hidden" name="price" value="<?= $detail->TotalRate ?>">
										<input type="hidden" name="currency" value="<?= DISPLAY_CURRENCY ?>">
										<input type="hidden" name="roomType" value="<?= $detail->Type ?>">
										<input type="hidden" name="boardBasis" value="<?= $detail->BoardBasis ?>">
										<input type="hidden" name="bookingKey" value="<?= $detail->BookingKey ?>">
										<input type="hidden" name="adults" value="<?= $detail->Adults ?? 1 ?>">
										<input type="hidden" name="children" value="<?= $detail->Children ?? 0 ?>">
										<input type="hidden" name="totalRooms" value="<?= $detail->TotalRooms ?? 1 ?>">
										<input type="hidden" name="totalRate" value="<?= $detail->TotalRate ?>">
										<button type="submit" class="btn btn-primary rounded-0 ms-3">Reserve</button>
									</form>
									<ul class="mt-3">
										<li>
											Confirmation is immediate.
										</li>
										<li>
											<?= !empty($detail->TermsAndConditions) ? $detail->TermsAndConditions : 'No booking or credit card fees.' ?>
										</li>
									</ul>
								</div>
							</div>
						</div>
					</div>
					<!--  -->
					<hr>
				<?php } ?>
			<?php }else{ ?>
				<p>No accommodation found.</p>
			<?php } ?>
			
		</div>
		<!-- web -->

		<!-- mobile -->
		<!-- 1 -->
		<div class="col-md-12 mt-4 d-lg-none">
			<div class="card">
				<?php if (!empty($roomDetails)) {?>
					<?php foreach ($roomDetails as $detail) {?>
						<div class="card-body" data-aos="fade-up" data-aos-duration="1000">
							<?php
							$roomType = explode(',', $detail->Type);
							?>
					<h6 class="fw-bold"><?= $roomType[0] ?></h6>
					<p>Max number of sleeps: <i class="ri-user-fill"></i><i class="ri-user-fill"></i>
					</p>
					<p><i class="ri-hotel-bed-line ri-lg"></i> <?= $roomType[1] ?></p>

					<div class="col-md-12 d-flex gap-1 flex-wrap">
						<span class="small border p-1">35m2</span>
						<span class="small border p-1">Air Conditioning</span>
						<span class="small border p-1">Free Wifi</span>
						<span class="small border p-1">Desk</span>
						<span class="small border p-1">Free toiletries</span>
						<span class="small border p-1">TV</span>
						<span class="small border p-1">Refrigerators</span>
						<span class="small border p-1">Microwave</span>
						<span class="small border p-1">Ironing facilities</span>
						<span class="small border p-1">Coffee/Tea maker</span>
						<span class="small border p-1">Hair drier</span>
					</div>

					<div class="col-md-12 mt-3">
						<h6 class="fw-bold">Your Choices</h6>

						<div class="row">
							<div class="col-1">
								<p class="text-success"><i class="ri-cup-line ri-lg"></i></p>
							</div>
							<div class="col-11">
								<p class="small text-success"><?= $detail->BoardBasis ?>
									included</p>
							</div>
						</div>

						<div class="row">
							<div class="col-1">
								<p class="text-success"><i class="ri-check-line ri-lg"></i></p>
							</div>
							<div class="col-11">
								<p class="small text-success"> <span class="fw-bold"><?= $detail->CancellationPolicy->Refundable ?>
                                            cancellation</span> until 11:49 PM
									on <?= $departureDate->format('F j, Y') ?>.</p>
							</div>
						</div>

						<div class="row">
							<div class="col-1">
								<p class="text-success"><i class="ri-check-line ri-lg"></i></p>
							</div>
							<div class="col-11">
								<p class="small text-success"><span class="fw-bold">NO PAYMENT NEEDED -</span> pay
									at the hotel</p>

							</div>
						</div>

						<div class="row">
							<div class="col-1">
								<p><i class="ri-price-tag-3-line ri-lg"></i></p>
							</div>
							<div class="col-11">
								<p class="small"> Discount available</p>
							</div>
						</div>
					</div>

					<div class="col-md-12 mt-3">
						<h6 class="fw-bold">Rooms</h6>
						<div class="d-flex">
							<div class="col-8">
								<div class="btn-group btn-group-sm" role="group" aria-label="Small button group">
									<button type="button" class="btn btn-outline-dark rounded-0 fw-bold">-</button>
									<button type="button" class="btn btn-outline-dark border-0 fw-bold">0</button>
									<button type="button" class="btn btn-outline-dark rounded-0 fw-bold">+</button>
								</div>
							</div>
							<div class="col-4">
								<a href="#" class="text-success small text-decoration-underline">View Pictures</a>
							</div>
						</div>
						<div class="col-md-12 mt-5">
							<form action="<?php echo site_url('booking/index')  ?>" method="post">
								<input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
								<input type="hidden" name="searchSessionId" value="<?= $apiResponse->SearchSessionId ?>">
								<input type="hidden" name="arrivalDate" value="<?= $defaults->arrival ?>">
								<input type="hidden" name="departureDate" value="<?= $defaults->departure ?>">
								<input type="hidden" name="countryCode" value="<?= $apiResponse->CountryCode ?>">
								<input type="hidden" name="cityCode" value="<?= $hotelDetails->Hotels->CityCode ?>">
								<input type="hidden" name="hotelId" value="<?= $hotelDetails->Hotels->HotelId ?>">
								<input type="hidden" name="hotelName" value="<?= $hotelDetails->Hotels->HotelName ?? '' ?>">
								<input type="hidden" name="price" value="<?= convertToNaira($detail->TotalRate, $apiCurrency) ?>">
								<input type="hidden" name="currency" value="<?= DISPLAY_CURRENCY ?>">
								<input type="hidden" name="roomType" value="<?= $detail->Type ?>">
								<input type="hidden" name="boardBasis" value="<?= $detail->BoardBasis ?>">
								<input type="hidden" name="bookingKey" value="<?= $detail->BookingKey ?>">
								<input type="hidden" name="adults" value="<?= $detail->Adults ?? 1 ?>">
								<input type="hidden" name="children" value="<?= $detail->Children ?? 0 ?>">
								<input type="hidden" name="totalRooms" value="<?= $detail->TotalRooms ?? 1 ?>">
								<input type="hidden" name="totalRate" value="<?= convertToNaira($detail->TotalRate, $apiCurrency) ?>">
								<button type="submit" class="btn btn-primary px-5 rounded-0 fw-bold">Reserve</button>
							</form>

							<ul class="mt-3 ms-n3 lh-1">
								<li>Confirmation is immediate.</li>
								<li><?= $detail->TermsAndConditions ?? 'No booking or credit card fees.' ?></li>
							</ul>
						</div>
						<div class="col-md-12 mt-3">
							<h4 class="text-success"><?= DISPLAY_CURRENCY_SYMBOL ?> <?= number_format(convertToNaira($detail->TotalRate, $apiCurrency), 2) ?></h4>
							<?php $taxAmountMobile = convertToNaira($detail->TotalRate, $apiCurrency) * 0.05; ?>
							<p class="small">+ <?= DISPLAY_CURRENCY_SYMBOL ?> <?= number_format($taxAmountMobile, 2) ?> taxes and charges</p>
						</div>
					</div>
				</div>
					<?php } ?>
				<?php }else{ ?>
					<p>No accommodation found.</p>
				<?php } ?>
			</div>
		</div>
		<!-- 1 -->

	</div>
	<!-- mobile -->
</section>


<!-- Tips -->
<section data-aos="fade-up" data-aos-duration="1000">
	<div class="container">
		<div class="row d-flex align-items-center">
			<div class="col-lg-12 p-4 grey-bg">
				<h5 class="fw-bold"><i class="fa-solid fa-check"></i> Tip: Stay flexible</h5>
				<p>Since your dates are a while from now, pick free cancellation to stay flexible. A change of plans
					is a breeze when you have free cancellation!</p>
			</div>
		</div>
	</div>
</section>
<!-- Tips -->

<!-- Guest reviews -->
<section data-aos="fade-up" data-aos-duration="1000" id="guestReviewsSection">
	<div class="container">
		<div class="row">
			<?php
			// Get reviews from hotel details if available
			$reviews = isset($hotelDetails->Reviews) ? (is_array($hotelDetails->Reviews) ? $hotelDetails->Reviews : [$hotelDetails->Reviews]) : [];
			$reviewCount = count($reviews);
			?>
			<h5 class="fw-bold mb-4">Guest Reviews <?= $reviewCount > 0 ? '(' . $reviewCount . ')' : '' ?></h5>
			<?php if ($reviewCount > 0): ?>
				<?php foreach (array_slice($reviews, 0, 5) as $review): ?>
				<div class="col-lg col-sm-12">
					<h6 class="fw-bold"><?= isset($review->Title) ? htmlspecialchars($review->Title) : 'Guest Review' ?></h6>
					<?php if (isset($review->Rating)): ?>
						<?php for ($i = 0; $i < min((int)$review->Rating, 5); $i++): ?>
							<i class="ri-star-fill text-warning"></i>
						<?php endfor; ?>
					<?php else: ?>
						<img src="<?= base_url('assets/images/hotel/rating.svg') ?>" class="img-fluid mb-3" alt="">
					<?php endif; ?>
					<p><?= isset($review->Comment) ? htmlspecialchars($review->Comment) : '' ?></p>
					<p><?= isset($review->Date) ? date('F j, Y', strtotime($review->Date)) : '' ?></p>
				</div>
				<?php endforeach; ?>
			<?php else: ?>
				<div class="col-12">
					<p class="text-muted">No reviews available yet for this property.</p>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>
<!-- Guest reviews -->


<!-- Property question and answer -->
<section>
	<div class="container">
		<div class="row">
			<div class="col-lg-12">
				<h5 class="fw-bold">Property questions and answers</h5>
				<p class="small">Browse questions from guests for anything you want to know about the hotel and get
					a reply within the next few days</p>
			</div>
			<div class="row justify-content-between align-items-center g-3">
				<div class="col-lg-4">
					<div class="card">
						<div class="card-body">
							<p class="fw-bold"><i class="ri-question-line ri-lg"></i> Are pets allowed?</p>
							<p><i class="ri-chat-3-line ri-lg"></i> Yes pets are allowed in
								the hotel.</p>
							<p>Asked about deluxe room with two double
								beds.</p>
							<p>Answered on: April 2, 2022</p>
						</div>
					</div>
				</div>
				<div class="col-lg-4">
					<div class="card">
						<div class="card-body">
							<p class="fw-bold"><i class="ri-question-line ri-lg"></i> Are pets allowed?</p>
							<p><i class="ri-chat-3-line ri-lg"></i> Yes pets are allowed in
								the hotel.</p>
							<p>Asked about deluxe room with two double
								beds.</p>
							<p>Answered on: April 2, 2022</p>
						</div>
					</div>
				</div>
				<div class="col-lg-4 text-center" >
					<h5 class="fw-bold mb-2">Still looking?</h5>
					<a href="contact.html" class="btn btn-warning btn-lg px-5 fw-bold mb-3">Ask a question</a>
					<div class="mt-2" id="facilitiesSection">
						<a href="" class="text-success small">See more questions(11)</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
<!-- Property question and answer -->

<!-- Facilities -->
<section data-aos="fade-up" data-aos-duration="1000" >
	<div class="container">
		<div class="row">
			<div class="col-lg-12">
				<h5 class="fw-bold">Facilities of <?= $defaults->hotelName ?></h5>
				<div class="row mt-4">
					<div class="col-lg-4">
						<div class="row">
							<div class="col-1">
								<p><i class="ri-bear-smile-line ri-lg"></i></p>
							</div>
							<div class="col-11">
								<h6 class="fw-bold">Pets</h6>
							</div>
						</div>
						<div class="row">
							<div class="col-1">
								<p><i class="ri-check-line ri-lg"></i></p>
							</div>
							<div class="col-11">
								<p>Pets are allowed, charges may apply</p>
							</div>
						</div>

					</div>
					<div class="col-lg-4">
						<div class="row">
							<div class="col-1">
								<p><i class="ri-parking-line ri-lg"></i></p>
							</div>
							<div class="col-11">
								<h6 class="fw-bold">Parking</h6>
							</div>
						</div>

						<div class="row">
							<div class="col-1">
								<p><i class="ri-check-line ri-lg"></i></p>
							</div>
							<div class="col-11">
								<p>Free public parking is available</p>
							</div>
						</div>

						<div class="row">
							<div class="col-1">
								<p><i class="ri-check-line ri-lg"></i></p>
							</div>
							<div class="col-11">
								<p>Accessibility parking</p>
							</div>
						</div>
					</div>
					<div class="col-lg-4">
						<div class="row">
							<div class="col-1">
								<p><i class="ri-wifi-line ri-lg"></i></p>
							</div>
							<div class="col-11">
								<h6 class="fw-bold">Internet</h6>
							</div>
						</div>
						<div class="row">
							<div class="col-1">
								<p><i class="ri-check-line ri-lg"></i></p>
							</div>
							<div class="col-11">
								<p>Wi-fi is available in all areas and is free of charge.</p>
							</div>
						</div>
					</div>
				</div>

				<div class="row mt-4">
					<div class="col-lg-4">
						<div class="row">
							<div class="col-1">
								<p><i class="ri-tv-2-line ri-lg"></i></p>
							</div>
							<div class="col-11">
								<h6 class="fw-bold m-1">Media and Technology</h6>
							</div>
						</div>

						<div class="row">
							<div class="col-1">
								<p><i class="ri-check-line ri-lg"></i></p>
							</div>
							<div class="col-11">
								<p>Television</p>
							</div>
						</div>

						<div class="row">
							<div class="col-1">
								<p><i class="ri-check-line ri-lg"></i></p>
							</div>
							<div class="col-11">
								<p>Cable channels</p>
							</div>
						</div>
					</div>
					<div class="col-lg-4">
						<div class="row">
							<div class="col-1">
								<p><i class="ri-cup-line ri-lg"></i></p>
							</div>
							<div class="col-11">
								<h6 class="fw-bold">Food and drink</h6>
							</div>
						</div>

						<div class="row">
							<div class="col-1">
								<p><i class="ri-check-line ri-lg"></i></p>
							</div>
							<div class="col-11">
								<p>Tea and coffe maker</p>
							</div>
						</div>
					</div>
					<div class="col-lg-4">
						<div class="row">
							<div class="col-1">
								<p><i class="ri-24-hours-line ri-lg"></i></p>
							</div>
							<div class="col-11">
								<h6 class="fw-bold">Customer Service</h6>
							</div>
						</div>

						<div class="row">
							<div class="col-1">
								<p><i class="ri-check-line ri-lg"></i></p>
							</div>
							<div class="col-11">
								<p>Front desk 24 hours, 7 days a week</p>
							</div>
						</div>
					</div>
				</div>

				<div class="row mt-4">
					<div class="col-lg-4">
						<div class="row">
							<div class="col-1">
								<p><i class="ri-service-line ri-lg"></i></p>
							</div>
							<div class="col-11">
								<h6 class="fw-bold">Services</h6>
							</div>
						</div>

						<div class="row">
							<div class="col-1">
								<p><i class="ri-check-line ri-lg"></i></p>
							</div>
							<div class="col-11">
								<p>Laundry</p>
							</div>
						</div>

						<div class="row">
							<div class="col-1">
								<p><i class="ri-check-line ri-lg"></i></p>
							</div>
							<div class="col-11">
								<p>Meeting/banquet facilities</p>
							</div>
						</div>
					</div>
					<div class="col-lg-4">
						<div class="row">
							<div class="col-1">
								<p><i class="ri-wheelchair-line ri-lg"></i></p>
							</div>
							<div class="col-11">
								<h6 class="fw-bold">Accessibility</h6>
							</div>
						</div>

						<div class="row">
							<div class="col-1">
								<p><i class="ri-check-line ri-lg"></i></p>
							</div>
							<div class="col-11">
								<p>Wheelchair accessible</p>
							</div>
						</div>

						<div class="row">
							<div class="col-1">
								<p><i class="ri-check-line ri-lg"></i></p>
							</div>
							<div class="col-11">
								<p>Visual aids</p>
							</div>
						</div>
					</div>
					<div class="col-lg-4">
						<div class="row">
							<div class="col-1">
								<p><i class="ri-hospital-line ri-lg"></i></p>
							</div>
							<div class="col-11">
								<h6 class="fw-bold">Health and wellness facilities</h6>
							</div>
						</div>

						<div class="row">
							<div class="col-1">
								<p><i class="ri-check-line ri-lg"></i></p>
							</div>
							<div class="col-11">
								<p>health and fitness center</p>
							</div>
						</div>
					</div>
				</div>
				<div class="row mt-4">
					<div class="col-lg-4">
						<div class="row">
							<div class="col-1">
								<p><i class="ri-chat-3-line ri-lg"></i></p>
							</div>
							<div class="col-11">
								<h6 class="fw-bold">Language spoken</h6>
							</div>
						</div>

						<div class="row">
							<div class="col-1">
								<p><i class="ri-check-line ri-lg"></i></p>
							</div>
							<div class="col-11">
								<p>English</p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
<!-- Facilities -->

<!-- Hotel rules -->
<section data-aos="fade-up" data-aos-duration="1000" id="hotelRulesSection">
	<div class="container">
		<div class="row">
			<div class="col-lg-12 mb-3">
				<h5 class="fw-bold">Hotel rules</h5>
			</div>
			<div class="col-lg-12 bg-secondary p-4">
				<div class="row">
					<div class="col-lg-12">
						<div class="row">
							<div class="col-lg-3">
								<p class="fw-bold"><i class="ri-error-warning-line ri-lg"></i>
									Cancellation/payment</p>
							</div>
							<div class="col-lg-9">
								<p>Cancellation and prepayment policies vary according to accommodations type. Check
									what conditions might apply to each option when making your selection.</p>
							</div>
						</div>

						<div class="row">
							<div class="col-lg-3">
								<p class="fw-bold"><i class="ri-error-warning-line ri-lg"></i>
									Refundable damage</p>
							</div>
							<div class="col-lg-9">
								<p>A damage deposit of USD 100 is required on arrival. That's about 42575.03NGN.
									This will
									be collected by credit card. You should be reimbursed within 7 days of
									check-out. Your
									deposit will be refunded in full by credit card, subject to an inspection of the
									property.</p>
							</div>
						</div>

						<div class="row">
							<div class="col-lg-3">
								<p class="fw-bold"><i class="ri-group-line ri-lg"></i> Cancellation/payment</p>
							</div>
							<div class="col-lg-9">
								<p>When booking more than 9 rooms, different policies and additional supplements
									may apply.</p>
							</div>
						</div>

						<div class="row">
							<div class="col-lg-3">
								<p class="fw-bold"><i class="ri-bear-smile-line ri-lg"></i> Pets</p>
							</div>
							<div class="col-lg-9">
								<p>Pets are allowed. Charges may apply.</p>
							</div>
						</div>

						<div class="row">
							<div class="col-lg-3">
								<p class="fw-bold"><i class="ri-bank-card-line ri-lg"></i> Cards accepted</p>
							</div>
							<div class="col-lg-9">
								<img src="<?= base_url('assets/images/hotel/payment.png') ?>" class="img-fluid" alt="">
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
<!-- Hotel rules -->

<!-- Fine print -->
<section data-aos="fade-up" data-aos-duration="1000" id="theFinePrintSection">
	<div class="container">
		<div class="row">
			<div class="col-md-12 d-flex">
				<div class="me-auto">
					<h5 class="fw-bold">The fine print</h5>
				</div>
				<div class="ms-auto">
					<a href="#" onclick="document.getElementById('pricesSection').scrollIntoView({ behavior: 'smooth' });" type="button" class="btn btn-primary">See Availability</a>
				</div>
			</div>

			<div class="col-lg-12 bg-secondary mt-4 p-4">
				<p>Guests must show a valid photo ID and a credit card upon check-in. Please note that special
					requests cannot be guaranteed and are subject to availability upon check-in. Additional charges
					may apply.
				</p>
				<p>Food and beverage services at this property may be limited or unavailable due to the coronavirus
					(COVID-19).</p>
				<p>Due to the coronavirus (COVID-19), this property is taking steps to protect the safety of guests
					and staff. Certain services and amenities may be reduced or unavailable as a result.</p>

				<p>Spa and gym facilities at this property are unavailable due to the coronavirus (COVID-19).</p>

				<p> Due to the coronavirus (COVID-19), wearing a face mask is mandatory in all indoor common areas.
				</p>

				<p>Guests are required to show a photo ID and credit card upon check-in. Please note that all
					Special Requests are subject to availability and additional charges may apply.</p>

				<p>damage deposit of USD 100 is required on arrival. That's about 42575.03NGN. This will be
					collected by credit card. You should be reimbursed within 7 days of check-out. Your deposit will
					be refunded in full by credit card, subject to an inspection of the property.</p>
			</div>
		</div>
	</div>
</section>
<!-- Fine print -->


<!-- similar hotels -->
<section class="similar-hotels" data-aos="fade-up" data-aos-duration="1000">
	<div class="container">
		<div class="row">
			<div class="col-md-12 mb-3">
				<h5 class="fw-bold">Other hotels like Jenos hotel</h5>
			</div>
			<div class="col-lg-12">
				<div class="main-gallery js-flickity"
					 data-flickity-options='{ "cellAlign": "center", "contain": "true", "freeScroll": "true", "wrapAround": "true", "autoPlay": true }'>
					<!--  -->
					<div class="card carousel-cell border-0">
						<div class="card-body">
							<div class="row g-3">
								<div class="col-lg-4">
									<img src="<?= base_url('assets/images/hotels-and-location/hotel-5.png') ?>" width="100%"
										 height="auto" class="img-fluid rounded" alt="">
								</div>
								<div class="col-lg-8">
									<div class="row">
										<div class="col-lg-12">
											<div class="row">
												<div class="col-lg-9 col-6">
													<h5 class="fw-bold mb-n0">Golden Tulip</h5>
													<div class="col-lg-4">
														<img src="<?= base_url('assets/images/hotel/rating.svg') ?>" class="" alt="">
													</div>
												</div>

												<div class="col-lg-3 col-6 d-flex">
													<div class="col-8">
														<h6 class="fw-bold">Excellent</h6>
														<p class="small">4,325 reviews</p>
													</div>
													<div class="col-4">
														<span class="badge bg-warning text-primary p-3">9.2</span>
													</div>
												</div>
											</div>

											<div class="row">
												<div class="mt-2">
													<a href="hotel-2.html"
													   class="me-1 text-decoration-underline">Accra</a> &bull; <a
														href="#" class="mx-1 text-decoration-underline">Show on
														map</a>
													&bull;
													<span class="small mx-1">1km from center</span>
												</div>
											</div>

											<div class="row mt-4 align-items-end">
												<div class="col-lg-8">
													<p class="">Deluxe King Room</p>
													<p>1 King bed</p>
													<p class="text-success fw-bold">Breakfast included</p>
													<p class="text-success fw-bold">Free cancellation. No payment
														needed</p>
												</div>
												<div class="col-lg-4 text-lg-end">
													<p>1 Night, 1 Adult</p>
													<h5 class="text-success fw-bold">NGN43,000</h5>
													<p class="small">+ NGN7,500 taxes and charges</p>

													<a href="hotel-2.html" class="btn btn-primary">
														See availability
													</a>

												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<!--  -->
					</div>
					<!--  -->

					<!--  -->
					<div class="card carousel-cell border-0">
						<div class="card-body">
							<div class="row g-3">
								<div class="col-lg-4">
									<img src="<?= base_url('assets/images/hotel/hotel-1.png') ?>" width="100%" height="auto"
										 class="img-fluid rounded" alt="">
								</div>
								<div class="col-lg-8">
									<div class="row">
										<div class="col-lg-12">
											<div class="row">
												<div class="col-lg-9 col-6">
													<h5 class="fw-bold mb-n0">Golden Tulip</h5>
													<div class="col-lg-4">
														<img src="<?= base_url('assets/images/hotel/rating.svg') ?>" class="" alt="">
													</div>
												</div>

												<div class="col-lg-3 col-6 d-flex">
													<div class="col-8">
														<h6 class="fw-bold">Excellent</h6>
														<p class="small">4,325 reviews</p>
													</div>
													<div class="col-4">
														<span class="badge bg-warning text-primary p-3">9.2</span>
													</div>
												</div>
											</div>

											<div class="row">
												<div class="mt-2">
													<a href="hotel-2.html"
													   class="me-1 text-decoration-underline">Accra</a> &bull; <a
														href="#" class="mx-1 text-decoration-underline">Show on
														map</a>
													&bull;
													<span class="small mx-1">1km from center</span>
												</div>
											</div>

											<div class="row mt-4 align-items-end">
												<div class="col-lg-8">
													<p class="">Deluxe King Room</p>
													<p>1 King bed</p>
													<p class="text-success fw-bold">Breakfast included</p>
													<p class="text-success fw-bold">Free cancellation. No payment
														needed</p>
												</div>
												<div class="col-lg-4 text-lg-end">
													<p>1 Night, 1 Adult</p>
													<h5 class="text-success fw-bold">NGN43,000</h5>
													<p class="small">+ NGN7,500 taxes and charges</p>

													<a href="hotel-2.html" class="btn btn-primary">
														See availability
													</a>

												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<!--  -->
					</div>
					<!--  -->

					<!--  -->
					<div class="card carousel-cell border-0">
						<div class="card-body">
							<div class="row g-3">
								<div class="col-lg-4">
									<img src="<?= base_url('assets/images/hotel/hotel-2.png') ?>" width="100%" height="auto"
										 class="img-fluid rounded" alt="">
								</div>
								<div class="col-lg-8">
									<div class="row">
										<div class="col-lg-12">
											<div class="row">
												<div class="col-lg-9 col-6">
													<h5 class="fw-bold mb-n0">Golden Tulip</h5>
													<div class="col-lg-4">
														<img src="<?= base_url('assets/images/hotel/rating.svg') ?>" class="" alt="">
													</div>
												</div>

												<div class="col-lg-3 col-6 d-flex">
													<div class="col-8">
														<h6 class="fw-bold">Excellent</h6>
														<p class="small">4,325 reviews</p>
													</div>
													<div class="col-4">
														<span class="badge bg-warning text-primary p-3">9.2</span>
													</div>
												</div>
											</div>

											<div class="row">
												<div class="mt-2">
													<a href="hotel-2.html"
													   class="me-1 text-decoration-underline">Accra</a> &bull; <a
														href="#" class="mx-1 text-decoration-underline">Show on
														map</a>
													&bull;
													<span class="small mx-1">1km from center</span>
												</div>
											</div>

											<div class="row mt-4 align-items-end">
												<div class="col-lg-8">
													<p class="">Deluxe King Room</p>
													<p>1 King bed</p>
													<p class="text-success fw-bold">Breakfast included</p>
													<p class="text-success fw-bold">Free cancellation. No payment
														needed</p>
												</div>
												<div class="col-lg-4 text-lg-end">
													<p>1 Night, 1 Adult</p>
													<h5 class="text-success fw-bold">NGN43,000</h5>
													<p class="small">+ NGN7,500 taxes and charges</p>

													<a href="hotel-2.html" class="btn btn-primary">
														See availability
													</a>

												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<!--  -->
					</div>
					<!--  -->


					<!--  -->
					<div class="card carousel-cell border-0">
						<div class="card-body">
							<div class="row g-3">
								<div class="col-lg-4">
									<img src="<?= base_url('assets/images/hotel/hotel-3.png') ?>" width="100%" height="auto"
										 class="img-fluid rounded" alt="">
								</div>
								<div class="col-lg-8">
									<div class="row">
										<div class="col-lg-12">
											<div class="row">
												<div class="col-lg-9 col-6">
													<h5 class="fw-bold mb-n0">Golden Tulip</h5>
													<div class="col-lg-4">
														<img src="<?= base_url('assets/images/hotel/rating.svg') ?>" class="" alt="">
													</div>
												</div>

												<div class="col-lg-3 col-6 d-flex">
													<div class="col-8">
														<h6 class="fw-bold">Excellent</h6>
														<p class="small">4,325 reviews</p>
													</div>
													<div class="col-4">
														<span class="badge bg-warning text-primary p-3">9.2</span>
													</div>
												</div>
											</div>

											<div class="row">
												<div class="mt-2">
													<a href="hotel-2.html"
													   class="me-1 text-decoration-underline">Accra</a> &bull; <a
														href="#" class="mx-1 text-decoration-underline">Show on
														map</a>
													&bull;
													<span class="small mx-1">1km from center</span>
												</div>
											</div>

											<div class="row mt-4 align-items-end">
												<div class="col-lg-8">
													<p class="">Deluxe King Room</p>
													<p>1 King bed</p>
													<p class="text-success fw-bold">Breakfast included</p>
													<p class="text-success fw-bold">Free cancellation. No payment
														needed</p>
												</div>
												<div class="col-lg-4 text-lg-end">
													<p>1 Night, 1 Adult</p>
													<h5 class="text-success fw-bold">NGN43,000</h5>
													<p class="small">+ NGN7,500 taxes and charges</p>

													<a href="hotel-2.html" class="btn btn-primary">
														See availability
													</a>

												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<!--  -->
					</div>
					<!--  -->
				</div>
			</div>
		</div>
	</div>
</section>
<!-- similar hotels -->
