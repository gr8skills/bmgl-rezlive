<?php
/**
 * Convert USD price to NGN (Nigerian Naira)
 * @param float|string $price Price in USD
 * @param string $sourceCurrency Source currency code (default USD)
 * @return float Price in NGN
 */
if (!function_exists('convertToNaira')) {
	function convertToNaira($price, $sourceCurrency = 'USD') {
		$price = (float) $price;
		if (strtoupper($sourceCurrency) === 'USD') {
			return $price * USD_TO_NGN_RATE;
		}
		return $price;
	}
}

// Get API currency for conversion
$apiCurrency = isset($apiResponse->Currency) ? (string)$apiResponse->Currency : 'USD';
?>

<section class="hotel-bookings">
	<div class="container">
		<div class="row mb-5 g-3">
			<div class="col-md-4" data-aos="fade-up" data-aos-duration="1000">
				<div class="card border-0" data-aos="fade-up" data-aos-duration="1000">
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
									   id="searchBox" placeholder="Search" value="<?= htmlspecialchars($defaults->location) ?>">

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
							$arrival = DateTime::createFromFormat('d/m/Y', (string)$defaults->Booking->ArrivalDate);
							$departure = DateTime::createFromFormat('d/m/Y', (string)$defaults->Booking->DepartureDate);
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

				<div class="card border-0 mt-4 d-none d-lg-block" id="filterCard">
					<div class="card-header bg-white text-primary" data-aos="fade-up" data-aos-duration="1000">
						<h5 class="fw-bold">Filter by:</h5>
					</div>
					<div class="card-body">
						<h6 class="fw-bold mb-3" data-aos="fade-up" data-aos-duration="1000">Your budget (per night)
						</h6>
						<div data-aos="fade-up" data-aos-duration="1000">
							<div class="d-flex" data-aos="fade-up" data-aos-duration="1000">
								<div class="form-check me-auto">
									<input class="form-check-input filter-price" type="checkbox" data-min="0" data-max="20000" id="price0">
									<label class="form-check-label" for="price0">
										₦0 - ₦20,000
									</label>
								</div>
								<span class="ms-auto filter-count" data-filter="price0">0</span>
							</div>

							<div class="d-flex mt-3" data-aos="fade-up" data-aos-duration="1000">
								<div class="form-check me-auto">
									<input class="form-check-input filter-price" type="checkbox" data-min="20000" data-max="40000" id="price1">
									<label class="form-check-label" for="price1">
										₦20,000 - ₦40,000
									</label>
								</div>
								<span class="ms-auto filter-count" data-filter="price1">0</span>
							</div>

							<div class="d-flex mt-3" data-aos="fade-up" data-aos-duration="1000">
								<div class="form-check me-auto">
									<input class="form-check-input filter-price" type="checkbox" data-min="40000" data-max="60000" id="price2">
									<label class="form-check-label" for="price2">
										₦40,000 - ₦60,000
									</label>
								</div>
								<span class="ms-auto filter-count" data-filter="price2">0</span>
							</div>

							<div class="d-flex mt-3" data-aos="fade-up" data-aos-duration="1000">
								<div class="form-check me-auto">
									<input class="form-check-input filter-price" type="checkbox" data-min="60000" data-max="80000" id="price3">
									<label class="form-check-label" for="price3">
										₦60,000 - ₦80,000
									</label>
								</div>
								<span class="ms-auto filter-count" data-filter="price3">0</span>
							</div>

							<div class="d-flex mt-3">
								<div class="form-check me-auto">
									<input class="form-check-input filter-price" type="checkbox" data-min="80000" data-max="100000" id="price4">
									<label class="form-check-label" for="price4">
										₦80,000 - ₦100,000
									</label>
								</div>
								<span class="ms-auto filter-count" data-filter="price4">0</span>
							</div>

							<div class="d-flex mt-3" data-aos="fade-up" data-aos-duration="1000">
								<div class="form-check me-auto">
									<input class="form-check-input filter-price" type="checkbox" data-min="100000" data-max="999999999" id="price5">
									<label class="form-check-label" for="price5">
										₦100,000+
									</label>
								</div>
								<span class="ms-auto filter-count" data-filter="price5">0</span>
							</div>
							<hr>
							<div class="" data-aos="fade-up" data-aos-duration="1000">
								<h6 class="fw-bold">Health and safety</h6>
								<div class="d-flex">
									<div class="form-check me-auto">
										<input class="form-check-input filter-amenity" type="checkbox" value="health-safety"
											   id="healthSafety">
										<label class="form-check-label" for="healthSafety">
											Hotels that take health and safety measures
										</label>
									</div>
									<span class="ms-auto total-hotel-count">0</span>
								</div>
							</div>
							<hr>
							<h6 class="fw-bold mb-3" data-aos="fade-up" data-aos-duration="1000">Popular filters
							</h6>
							<div class="d-flex" data-aos="fade-up" data-aos-duration="1000">
								<div class="form-check me-auto">
									<input class="form-check-input filter-amenity" type="checkbox" id="amenity-hotels">
									<label class="form-check-label" for="amenity-hotels">
										Hotels
									</label>
								</div>
								<span class="ms-auto total-hotel-count">0</span>
							</div>

							<div class="d-flex mt-3" data-aos="fade-up" data-aos-duration="1000">
								<div class="form-check me-auto">
									<input class="form-check-input filter-amenity" type="checkbox" id="amenity-indoor-pool">
									<label class="form-check-label" for="amenity-indoor-pool">
										Indoor pool
									</label>
								</div>
								<span class="ms-auto total-hotel-count">0</span>
							</div>

							<div class="d-flex mt-3" data-aos="fade-up" data-aos-duration="1000">
								<div class="form-check me-auto">
									<input class="form-check-input filter-amenity" type="checkbox" id="amenity-bnb">
									<label class="form-check-label" for="amenity-bnb">
										Bed and breakfast
									</label>
								</div>
								<span class="ms-auto total-hotel-count">0</span>
							</div>

							<div class="d-flex mt-3" data-aos="fade-up" data-aos-duration="1000">
								<div class="form-check me-auto">
									<input class="form-check-input filter-amenity" type="checkbox" id="amenity-jacuzzi">
									<label class="form-check-label" for="amenity-jacuzzi">
										Hot tub/ Jacuzzi
									</label>
								</div>
								<span class="ms-auto total-hotel-count">0</span>
							</div>

							<div class="d-flex mt-3" data-aos="fade-up" data-aos-duration="1000">
								<div class="form-check me-auto">
									<input class="form-check-input filter-amenity" type="checkbox" id="amenity-hostels">
									<label class="form-check-label" for="amenity-hostels">
										Hostels
									</label>
								</div>
								<span class="ms-auto total-hotel-count">0</span>
							</div>

							<div class="d-flex mt-3" data-aos="fade-up" data-aos-duration="1000">
								<div class="form-check me-auto">
									<input class="form-check-input filter-amenity" type="checkbox" id="amenity-playground">
									<label class="form-check-label" for="amenity-playground">
										Playground
									</label>
								</div>
								<span class="ms-auto total-hotel-count">0</span>
							</div>

							<div class="d-flex mt-3" data-aos="fade-up" data-aos-duration="1000">
								<div class="form-check me-auto">
									<input class="form-check-input filter-amenity" type="checkbox" id="amenity-spa">
									<label class="form-check-label" for="amenity-spa">
										Spa
									</label>
								</div>
								<span class="ms-auto total-hotel-count">0</span>
							</div>

							<div class="d-flex mt-3" data-aos="fade-up" data-aos-duration="1000">
								<div class="form-check me-auto">
									<input class="form-check-input filter-amenity" type="checkbox" id="amenity-villas">
									<label class="form-check-label" for="amenity-villas">
										Villas
									</label>
								</div>
								<span class="ms-auto total-hotel-count">0</span>
							</div>
							<hr>
							<div class="" data-aos="fade-up" data-aos-duration="1000">
								<h6 class="fw-bold">Sustainability</h6>
								<div class="d-flex">
									<div class="form-check me-auto">
										<input class="form-check-input filter-amenity" type="checkbox" id="amenity-sustainability">
										<label class="form-check-label" for="amenity-sustainability">
											Travel sustainability properties
										</label>
									</div>
									<span class="ms-auto total-hotel-count">0</span>
								</div>
							</div>
							<hr>
							<h6 class="fw-bold mb-3" data-aos="fade-up" data-aos-duration="1000">Star Rating</h6>
							<div class="d-flex" data-aos="fade-up" data-aos-duration="1000">
								<div class="form-check me-auto">
									<input class="form-check-input filter-star" type="checkbox" data-star="1" id="star1">
									<label class="form-check-label" for="star1">
										<i class="ri-star-fill text-warning"></i>
									</label>
								</div>
								<span class="ms-auto filter-count" data-filter="star1">0</span>
							</div>

							<div class="d-flex mt-3" data-aos="fade-up" data-aos-duration="1000">
								<div class="form-check me-auto">
									<input class="form-check-input filter-star" type="checkbox" data-star="2" id="star2">
									<label class="form-check-label" for="star2">
										<i class="ri-star-fill text-warning"></i><i class="ri-star-fill text-warning"></i>
									</label>
								</div>
								<span class="ms-auto filter-count" data-filter="star2">0</span>
							</div>

							<div class="d-flex mt-3" data-aos="fade-up" data-aos-duration="1000">
								<div class="form-check me-auto">
									<input class="form-check-input filter-star" type="checkbox" data-star="3" id="star3">
									<label class="form-check-label" for="star3">
										<i class="ri-star-fill text-warning"></i><i class="ri-star-fill text-warning"></i><i class="ri-star-fill text-warning"></i>
									</label>
								</div>
								<span class="ms-auto filter-count" data-filter="star3">0</span>
							</div>

							<div class="d-flex mt-3" data-aos="fade-up" data-aos-duration="1000">
								<div class="form-check me-auto">
									<input class="form-check-input filter-star" type="checkbox" data-star="4" id="star4">
									<label class="form-check-label" for="star4">
										<i class="ri-star-fill text-warning"></i><i class="ri-star-fill text-warning"></i><i class="ri-star-fill text-warning"></i><i class="ri-star-fill text-warning"></i>
									</label>
								</div>
								<span class="ms-auto filter-count" data-filter="star4">0</span>
							</div>

							<div class="d-flex mt-3" data-aos="fade-up" data-aos-duration="1000">
								<div class="form-check me-auto">
									<input class="form-check-input filter-star" type="checkbox" data-star="5" id="star5">
									<label class="form-check-label" for="star5">
										<i class="ri-star-fill text-warning"></i><i class="ri-star-fill text-warning"></i><i class="ri-star-fill text-warning"></i><i class="ri-star-fill text-warning"></i><i class="ri-star-fill text-warning"></i>
									</label>
								</div>
								<span class="ms-auto filter-count" data-filter="star5">0</span>
							</div>

							<div class="d-flex mt-3" data-aos="fade-up" data-aos-duration="1000">
								<div class="form-check me-auto">
									<input class="form-check-input filter-star" type="checkbox" data-star="0" id="star0">
									<label class="form-check-label" for="star0">
										Unrated
									</label>
								</div>
								<span class="ms-auto filter-count" data-filter="star0">0</span>
							</div>
							<hr>

							<div class="" data-aos="fade-up" data-aos-duration="1000">
								<h6 class="fw-bold" data-aos="fade-up" data-aos-duration="1000">Distance from the
									centre of <?= htmlspecialchars($defaults->location) ?></h6>
								<div class="d-flex" data-aos="fade-up" data-aos-duration="1000">
									<div class="form-check me-auto">
										<input class="form-check-input filter-amenity" type="checkbox">
										<label class="form-check-label">
											Less than 1km
										</label>
									</div>
									<span class="ms-auto total-hotel-count">0</span>
								</div>
								<div class="d-flex mt-3" data-aos="fade-up" data-aos-duration="1000">
									<div class="form-check me-auto">
										<input class="form-check-input filter-amenity" type="checkbox">
										<label class="form-check-label">
											Less than 3km
										</label>
									</div>
									<span class="ms-auto total-hotel-count">0</span>
								</div>
								<div class="d-flex mt-3" data-aos="fade-up" data-aos-duration="1000">
									<div class="form-check me-auto">
										<input class="form-check-input filter-amenity" type="checkbox">
										<label class="form-check-label">
											Less than 5km
										</label>
									</div>
									<span class="ms-auto total-hotel-count">0</span>
								</div>
							</div>

							<hr>
							<h6 class="fw-bold mb-3" data-aos="fade-up" data-aos-duration="1000">Fun things to do
							</h6>
							<div class="d-flex" data-aos="fade-up" data-aos-duration="1000">
								<div class="form-check me-auto">
									<input class="form-check-input filter-amenity" type="checkbox">
									<label class="form-check-label">
										Fitness center
									</label>
								</div>
								<span class="ms-auto total-hotel-count">0</span>
							</div>

							<div class="d-flex mt-3" data-aos="fade-up" data-aos-duration="1000">
								<div class="form-check me-auto">
									<input class="form-check-input filter-amenity" type="checkbox">
									<label class="form-check-label">
										Indoor pool
									</label>
								</div>
								<span class="ms-auto total-hotel-count">0</span>
							</div>

							<div class="d-flex mt-3" data-aos="fade-up" data-aos-duration="1000">
								<div class="form-check me-auto">
									<input class="form-check-input filter-amenity" type="checkbox">
									<label class="form-check-label">
										Golf course
									</label>
								</div>
								<span class="ms-auto total-hotel-count">0</span>
							</div>

							<div class="d-flex mt-3" data-aos="fade-up" data-aos-duration="1000">
								<div class="form-check me-auto">
									<input class="form-check-input filter-amenity" type="checkbox">
									<label class="form-check-label">
										Hot tub / Jacuzzi
									</label>
								</div>
								<span class="ms-auto total-hotel-count">0</span>
							</div>

							<div class="d-flex mt-3" data-aos="fade-up" data-aos-duration="1000">
								<div class="form-check me-auto">
									<input class="form-check-input filter-amenity" type="checkbox">
									<label class="form-check-label">
										Happy hour
									</label>
								</div>
								<span class="ms-auto total-hotel-count">0</span>
							</div>

							<div class="d-flex mt-3" data-aos="fade-up" data-aos-duration="1000">
								<div class="form-check me-auto">
									<input class="form-check-input filter-amenity" type="checkbox">
									<label class="form-check-label">
										Game room
									</label>
								</div>
								<span class="ms-auto total-hotel-count">0</span>
							</div>

							<div class="d-flex mt-3" data-aos="fade-up" data-aos-duration="1000">
								<div class="form-check me-auto">
									<input class="form-check-input filter-amenity" type="checkbox">
									<label class="form-check-label">
										Live sports events
									</label>
								</div>
								<span class="ms-auto total-hotel-count">0</span>
							</div>

							<div class="d-flex mt-3" data-aos="fade-up" data-aos-duration="1000">
								<div class="form-check me-auto">
									<input class="form-check-input filter-amenity" type="checkbox">
									<label class="form-check-label">
										Mini golf
									</label>
								</div>
								<span class="ms-auto total-hotel-count">0</span>
							</div>

							<div class="d-flex mt-3" data-aos="fade-up" data-aos-duration="1000">
								<div class="form-check me-auto">
									<input class="form-check-input filter-amenity" type="checkbox">
									<label class="form-check-label">
										Spa facilities
									</label>
								</div>
								<span class="ms-auto total-hotel-count">0</span>
							</div>

							<div class="d-flex mt-3" data-aos="fade-up" data-aos-duration="1000">
								<div class="form-check me-auto">
									<input class="form-check-input filter-amenity" type="checkbox">
									<label class="form-check-label">
										Stand-up comedy
									</label>
								</div>
								<span class="ms-auto total-hotel-count">0</span>
							</div>

							<div class="d-flex mt-3" data-aos="fade-up" data-aos-duration="1000">
								<div class="form-check me-auto">
									<input class="form-check-input filter-amenity" type="checkbox">
									<label class="form-check-label">
										Sauna
									</label>
								</div>
								<span class="ms-auto total-hotel-count">0</span>
							</div>

							<div class="d-flex mt-3" data-aos="fade-up" data-aos-duration="1000">
								<div class="form-check me-auto">
									<input class="form-check-input filter-amenity" type="checkbox">
									<label class="form-check-label">
										Playground
									</label>
								</div>
								<span class="ms-auto total-hotel-count">0</span>
							</div>

							<div class="d-flex mt-3" data-aos="fade-up" data-aos-duration="1000">
								<div class="form-check me-auto">
									<input class="form-check-input filter-amenity" type="checkbox">
									<label class="form-check-label">
										Canoeing
									</label>
								</div>
								<span class="ms-auto total-hotel-count">0</span>
							</div>

							<div class="d-flex mt-3" data-aos="fade-up" data-aos-duration="1000">
								<div class="form-check me-auto">
									<input class="form-check-input filter-amenity" type="checkbox">
									<label class="form-check-label">
										Beauty service
									</label>
								</div>
								<span class="ms-auto total-hotel-count">0</span>
							</div>

							<div class="d-flex mt-3" data-aos="fade-up" data-aos-duration="1000">
								<div class="form-check me-auto">
									<input class="form-check-input filter-amenity" type="checkbox">
									<label class="form-check-label">
										Spa lounge / Relation area
									</label>
								</div>
								<span class="ms-auto total-hotel-count">0</span>
							</div>

							<div class="d-flex mt-3" data-aos="fade-up" data-aos-duration="1000">
								<div class="form-check me-auto">
									<input class="form-check-input filter-amenity" type="checkbox">
									<label class="form-check-label">
										Bar
									</label>
								</div>
								<span class="ms-auto total-hotel-count">0</span>
							</div>

							<div class="d-flex mt-3" data-aos="fade-up" data-aos-duration="1000">
								<div class="form-check me-auto">
									<input class="form-check-input filter-amenity" type="checkbox">
									<label class="form-check-label">
										Movie night
									</label>
								</div>
								<span class="ms-auto total-hotel-count">0</span>
							</div>

							<div class="d-flex mt-3" data-aos="fade-up" data-aos-duration="1000">
								<div class="form-check me-auto">
									<input class="form-check-input filter-amenity" type="checkbox">
									<label class="form-check-label">
										Walking tour
									</label>
								</div>
								<span class="ms-auto total-hotel-count">0</span>
							</div>

							<div class="d-flex mt-3" data-aos="fade-up" data-aos-duration="1000">
								<div class="form-check me-auto">
									<input class="form-check-input filter-amenity" type="checkbox">
									<label class="form-check-label">
										Bike tour
									</label>
								</div>
								<span class="ms-auto total-hotel-count">0</span>
							</div>

							<div class="d-flex mt-3" data-aos="fade-up" data-aos-duration="1000">
								<div class="form-check me-auto">
									<input class="form-check-input filter-amenity" type="checkbox">
									<label class="form-check-label">
										Themed dinner
									</label>
								</div>
								<span class="ms-auto total-hotel-count">0</span>
							</div>
							<hr>

							<div class="" data-aos="fade-up" data-aos-duration="1000">
								<h6 class="fw-bold" data-aos="fade-up" data-aos-duration="1000">Landmarks</h6>
								<div class="d-flex" data-aos="fade-up" data-aos-duration="1000">
									<div class="form-check me-auto">
										<input class="form-check-input filter-amenity" type="checkbox">
										<label class="form-check-label">
											City center
										</label>
									</div>
									<span class="ms-auto total-hotel-count">0</span>
								</div>

								<div class="d-flex mt-3" data-aos="fade-up" data-aos-duration="1000">
									<div class="form-check me-auto">
										<input class="form-check-input filter-amenity" type="checkbox">
										<label class="form-check-label">
											Nationwide arena
										</label>
									</div>
									<span class="ms-auto total-hotel-count">0</span>
								</div>
							</div>
							<hr>
							<h6 class="fw-bold mb-3" data-aos="fade-up" data-aos-duration="1000">Review Score based
								on guests reviews</h6>
							<div class="d-flex" data-aos="fade-up" data-aos-duration="1000">
								<div class="form-check me-auto">
									<input class="form-check-input filter-amenity" type="checkbox">
									<label class="form-check-label">
										Wonderful:9+
									</label>
								</div>
								<span class="ms-auto total-hotel-count">0</span>
							</div>

							<div class="d-flex mt-3" data-aos="fade-up" data-aos-duration="1000">
								<div class="form-check me-auto">
									<input class="form-check-input filter-amenity" type="checkbox">
									<label class="form-check-label">
										Very Good:8+
									</label>
								</div>
								<span class="ms-auto total-hotel-count">0</span>
							</div>

							<div class="d-flex mt-3" data-aos="fade-up" data-aos-duration="1000">
								<div class="form-check me-auto">
									<input class="form-check-input filter-amenity" type="checkbox">
									<label class="form-check-label">
										Good:7+
									</label>
								</div>
								<span class="ms-auto total-hotel-count">0</span>
							</div>

							<div class="d-flex mt-3" data-aos="fade-up" data-aos-duration="1000">
								<div class="form-check me-auto">
									<input class="form-check-input filter-amenity" type="checkbox">
									<label class="form-check-label">
										Pleasant:6+
									</label>
								</div>
								<span class="ms-auto total-hotel-count">0</span>
							</div>
							<hr>

							<div class="" data-aos="fade-up" data-aos-duration="1000">
								<h6 class="fw-bold mb-3" data-aos="fade-up" data-aos-duration="1000">Room facilities
								</h6>
								<div class="d-flex" data-aos="fade-up" data-aos-duration="1000">
									<div class="form-check me-auto">
										<input class="form-check-input filter-amenity" type="checkbox">
										<label class="form-check-label">
											Kitchen
										</label>
									</div>
									<span class="ms-auto total-hotel-count">0</span>
								</div>

								<div class="d-flex mt-3" data-aos="fade-up" data-aos-duration="1000">
									<div class="form-check me-auto">
										<input class="form-check-input filter-amenity" type="checkbox">
										<label class="form-check-label">
											Air conditioning
										</label>
									</div>
									<span class="ms-auto total-hotel-count">0</span>
								</div>

								<div class="d-flex mt-3" data-aos="fade-up" data-aos-duration="1000">
									<div class="form-check me-auto">
										<input class="form-check-input filter-amenity" type="checkbox">
										<label class="form-check-label">
											Desk
										</label>
									</div>
									<span class="ms-auto total-hotel-count">0</span>
								</div>

								<div class="d-flex mt-3" data-aos="fade-up" data-aos-duration="1000">
									<div class="form-check me-auto">
										<input class="form-check-input filter-amenity" type="checkbox">
										<label class="form-check-label">
											Bath tub
										</label>
									</div>
									<span class="ms-auto total-hotel-count">0</span>
								</div>

								<div class="d-flex mt-3" data-aos="fade-up" data-aos-duration="1000">
									<div class="form-check me-auto">
										<input class="form-check-input filter-amenity" type="checkbox">
										<label class="form-check-label">
											Flat screen TV
										</label>
									</div>
									<span class="ms-auto total-hotel-count">0</span>
								</div>

								<div class="d-flex mt-3" data-aos="fade-up" data-aos-duration="1000">
									<div class="form-check me-auto">
										<input class="form-check-input filter-amenity" type="checkbox">
										<label class="form-check-label">
											Cofee / Tea maker
										</label>
									</div>
									<span class="ms-auto total-hotel-count">0</span>
								</div>
							</div>
							<hr>

							<div class="" data-aos="fade-up" data-aos-duration="1000">
								<h6 class="fw-bold mb-3" data-aos="fade-up" data-aos-duration="1000">Facilities</h6>
								<div class="d-flex" data-aos="fade-up" data-aos-duration="1000">
									<div class="form-check me-auto">
										<input class="form-check-input filter-amenity" type="checkbox">
										<label class="form-check-label">
											Non-smoking rooms
										</label>
									</div>
									<span class="ms-auto total-hotel-count">0</span>
								</div>

								<div class="d-flex mt-3" data-aos="fade-up" data-aos-duration="1000">
									<div class="form-check me-auto">
										<input class="form-check-input filter-amenity" type="checkbox">
										<label class="form-check-label">
											Parking
										</label>
									</div>
									<span class="ms-auto total-hotel-count">0</span>
								</div>

								<div class="d-flex mt-3" data-aos="fade-up" data-aos-duration="1000">
									<div class="form-check me-auto">
										<input class="form-check-input filter-amenity" type="checkbox">
										<label class="form-check-label">
											24-hour front desk
										</label>
									</div>
									<span class="ms-auto total-hotel-count">0</span>
								</div>

								<div class="d-flex mt-3" data-aos="fade-up" data-aos-duration="1000">
									<div class="form-check me-auto">
										<input class="form-check-input filter-amenity" type="checkbox">
										<label class="form-check-label">
											Free Wi-fi
										</label>
									</div>
									<span class="ms-auto total-hotel-count">0</span>
								</div>

								<div class="d-flex mt-3" data-aos="fade-up" data-aos-duration="1000">
									<div class="form-check me-auto">
										<input class="form-check-input filter-amenity" type="checkbox">
										<label class="form-check-label">
											Restaurant
										</label>
									</div>
									<span class="ms-auto total-hotel-count">0</span>
								</div>

								<div class="d-flex mt-3" data-aos="fade-up" data-aos-duration="1000">
									<div class="form-check me-auto">
										<input class="form-check-input filter-amenity" type="checkbox">
										<label class="form-check-label">
											Pet friendly
										</label>
									</div>
									<span class="ms-auto total-hotel-count">0</span>
								</div>

								<div class="d-flex mt-3" data-aos="fade-up" data-aos-duration="1000">
									<div class="form-check me-auto">
										<input class="form-check-input filter-amenity" type="checkbox">
										<label class="form-check-label">
											Room service
										</label>
									</div>
									<span class="ms-auto total-hotel-count">0</span>
								</div>

								<div class="d-flex mt-3" data-aos="fade-up" data-aos-duration="1000">
									<div class="form-check me-auto">
										<input class="form-check-input filter-amenity" type="checkbox">
										<label class="form-check-label">
											Fitness center
										</label>
									</div>
									<span class="ms-auto total-hotel-count">0</span>
								</div>

								<div class="d-flex mt-3" data-aos="fade-up" data-aos-duration="1000">
									<div class="form-check me-auto">
										<input class="form-check-input filter-amenity" type="checkbox">
										<label class="form-check-label">
											Airport shuttle
										</label>
									</div>
									<span class="ms-auto total-hotel-count">0</span>
								</div>

								<div class="d-flex mt-3" data-aos="fade-up" data-aos-duration="1000">
									<div class="form-check me-auto">
										<input class="form-check-input filter-amenity" type="checkbox">
										<label class="form-check-label">
											Facilities for disabled guests
										</label>
									</div>
									<span class="ms-auto total-hotel-count">0</span>
								</div>

								<div class="d-flex mt-3" data-aos="fade-up" data-aos-duration="1000">
									<div class="form-check me-auto">
										<input class="form-check-input filter-amenity" type="checkbox">
										<label class="form-check-label">
											Family room
										</label>
									</div>
									<span class="ms-auto total-hotel-count">0</span>
								</div>

								<div class="d-flex mt-3" data-aos="fade-up" data-aos-duration="1000">
									<div class="form-check me-auto">
										<input class="form-check-input filter-amenity" type="checkbox">
										<label class="form-check-label">
											Spa
										</label>
									</div>
									<span class="ms-auto total-hotel-count">0</span>
								</div>

								<div class="d-flex mt-3" data-aos="fade-up" data-aos-duration="1000">
									<div class="form-check me-auto">
										<input class="form-check-input filter-amenity" type="checkbox">
										<label class="form-check-label">
											Electronic charging room
										</label>
									</div>
									<span class="ms-auto total-hotel-count">0</span>
								</div>

								<div class="d-flex mt-3">
									<div class="form-check me-auto">
										<input class="form-check-input filter-amenity" type="checkbox">
										<label class="form-check-label">
											Swimming pool
										</label>
									</div>
									<span class="ms-auto total-hotel-count">0</span>
								</div>
							</div>
							<hr>

							<div class="" data-aos="fade-up" data-aos-duration="1000">
								<h6 class="fw-bold mb-3" data-aos="fade-up" data-aos-duration="1000">Property
									Accessibility</h6>
								<div class="d-flex" data-aos="fade-up" data-aos-duration="1000">
									<div class="form-check me-auto">
										<input class="form-check-input filter-amenity" type="checkbox">
										<label class="form-check-label">
											Wheelchair accessible
										</label>
									</div>
									<span class="ms-auto total-hotel-count">0</span>
								</div>

								<div class="d-flex mt-3" data-aos="fade-up" data-aos-duration="1000">
									<div class="form-check me-auto">
										<input class="form-check-input filter-amenity" type="checkbox">
										<label class="form-check-label">
											Toilet with grab rails
										</label>
									</div>
									<span class="ms-auto total-hotel-count">0</span>
								</div>

								<div class="d-flex mt-3" data-aos="fade-up" data-aos-duration="1000">
									<div class="form-check me-auto">
										<input class="form-check-input filter-amenity" type="checkbox">
										<label class="form-check-label">
											Raised toilet
										</label>
									</div>
									<span class="ms-auto total-hotel-count">0</span>
								</div>

								<div class="d-flex mt-3" data-aos="fade-up" data-aos-duration="1000">
									<div class="form-check me-auto">
										<input class="form-check-input filter-amenity" type="checkbox">
										<label class="form-check-label">
											Lowered sink
										</label>
									</div>
									<span class="ms-auto total-hotel-count">0</span>
								</div>

								<div class="d-flex mt-3" data-aos="fade-up" data-aos-duration="1000">
									<div class="form-check me-auto">
										<input class="form-check-input filter-amenity" type="checkbox">
										<label class="form-check-label">
											Bathroom emergency cord
										</label>
									</div>
									<span class="ms-auto total-hotel-count">0</span>
								</div>

								<div class="d-flex mt-3" data-aos="fade-up" data-aos-duration="1000">
									<div class="form-check me-auto">
										<input class="form-check-input filter-amenity" type="checkbox">
										<label class="form-check-label">
											Visual aids (Braille)
										</label>
									</div>
									<span class="ms-auto total-hotel-count">0</span>
								</div>

								<div class="d-flex mt-3" data-aos="fade-up" data-aos-duration="1000">
									<div class="form-check me-auto">
										<input class="form-check-input filter-amenity" type="checkbox">
										<label class="form-check-label">
											Visual aids (Tactile signs)
										</label>
									</div>
									<span class="ms-auto total-hotel-count">0</span>
								</div>

								<div class="d-flex mt-3" data-aos="fade-up" data-aos-duration="1000">
									<div class="form-check me-auto">
										<input class="form-check-input filter-amenity" type="checkbox">
										<label class="form-check-label">
											Auditory guidance
										</label>
									</div>
									<span class="ms-auto total-hotel-count">0</span>
								</div>
							</div>
							<hr>

							<div class="" data-aos="fade-up" data-aos-duration="1000">
								<h6 class="fw-bold mb-3" data-aos="fade-up" data-aos-duration="1000">Room
									Accessibility</h6>
								<div class="d-flex" data-aos="fade-up" data-aos-duration="1000">
									<div class="form-check me-auto">
										<input class="form-check-input filter-amenity" type="checkbox">
										<label class="form-check-label">
											Shower chair
										</label>
									</div>
									<span class="ms-auto total-hotel-count">0</span>
								</div>

								<div class="d-flex mt-3" data-aos="fade-up" data-aos-duration="1000">
									<div class="form-check me-auto">
										<input class="form-check-input filter-amenity" type="checkbox">
										<label class="form-check-label">
											Emergency cord in bathroom
										</label>
									</div>
									<span class="ms-auto total-hotel-count">0</span>
								</div>

								<div class="d-flex mt-3" data-aos="fade-up" data-aos-duration="1000">
									<div class="form-check me-auto">
										<input class="form-check-input filter-amenity" type="checkbox">
										<label class="form-check-label">
											Lower sink
										</label>
									</div>
									<span class="ms-auto total-hotel-count">0</span>
								</div>

								<div class="d-flex mt-3" data-aos="fade-up" data-aos-duration="1000">
									<div class="form-check me-auto">
										<input class="form-check-input filter-amenity" type="checkbox">
										<label class="form-check-label">
											Raised toilet
										</label>
									</div>
									<span class="ms-auto total-hotel-count">0</span>
								</div>

								<div class="d-flex mt-3" data-aos="fade-up" data-aos-duration="1000">
									<div class="form-check me-auto">
										<input class="form-check-input filter-amenity" type="checkbox">
										<label class="form-check-label">
											Walk-in shower
										</label>
									</div>
									<span class="ms-auto total-hotel-count">0</span>
								</div>

								<div class="d-flex mt-3" data-aos="fade-up" data-aos-duration="1000">
									<div class="form-check me-auto">
										<input class="form-check-input filter-amenity" type="checkbox">
										<label class="form-check-label">
											Roll-in shower
										</label>
									</div>
									<span class="ms-auto total-hotel-count">0</span>
								</div>

								<div class="d-flex mt-3" data-aos="fade-up" data-aos-duration="1000">
									<div class="form-check me-auto">
										<input class="form-check-input filter-amenity" type="checkbox">
										<label class="form-check-label">
											Adapted bath
										</label>
									</div>
									<span class="ms-auto total-hotel-count">0</span>
								</div>

								<div class="d-flex mt-3" data-aos="fade-up" data-aos-duration="1000">
									<div class="form-check me-auto">
										<input class="form-check-input filter-amenity" type="checkbox">
										<label class="form-check-label">
											Toilet grab with rails
										</label>
									</div>
									<span class="ms-auto total-hotel-count">0</span>
								</div>

								<div class="d-flex mt-3" data-aos="fade-up" data-aos-duration="1000">
									<div class="form-check me-auto">
										<input class="form-check-input filter-amenity" type="checkbox">
										<label class="form-check-label">
											Entire unit wheelchair accessible
										</label>
									</div>
									<span class="ms-auto total-hotel-count">0</span>
								</div>

								<div class="d-flex mt-3" data-aos="fade-up" data-aos-duration="1000">
									<div class="form-check me-auto" data-aos="fade-up" data-aos-duration="1000">
										<input class="form-check-input filter-amenity" type="checkbox">
										<label class="form-check-label">
											Upper floor accessible by elevator
										</label>
									</div>
									<span class="ms-auto total-hotel-count">0</span>
								</div>

								<div class="d-flex mt-3" data-aos="fade-up" data-aos-duration="1000">
									<div class="form-check me-auto" data-aos="fade-up" data-aos-duration="1000">
										<input class="form-check-input filter-amenity" type="checkbox">
										<label class="form-check-label">
											Entire unit located on ground floor
										</label>
									</div>
									<span class="ms-auto total-hotel-count">0</span>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="col-lg-12 mt-4" data-aos="fade-up" data-aos-duration="1000" id="mapSection">
					<div class="map position-relative">
						<iframe
							width="100%"
							height="300"
							style="border:0; border-radius: 8px;"
							loading="lazy"
							allowfullscreen
							referrerpolicy="no-referrer-when-downgrade"
							src="https://www.google.com/maps/embed/v1/place?key=AIzaSyBFw0Qbyq9zTFTd-tUY6dZWTgaQzuU17R8&q=<?= urlencode($defaults->location) ?>&zoom=12">
						</iframe>
					</div>
				</div>
			</div>
			<div class="col-lg-8" data-aos="fade-up" data-aos-duration="1000">
				<div class="d-flex justify-content-between" data-aos="fade-up" data-aos-duration="1000">
					<div class="col-lg-12 col-7">
						<h1 class="big-heading"><?= $defaults->location ?></h1>
						<h5 class="fw-bold"><?= $hotelCount ?> Hotels found</h5>
					</div>
					<div class="col-5 text-end d-lg-none">
						<a href="" class="btn btn-primary btn-lg"><i class="ri-sound-module-line"></i> Filter</a>
					</div>
				</div>
				<div class="d-flex justify-content-between align-items-center" data-aos="fade-up"
					 data-aos-duration="1000">
					<div class="col-lg-9 me-auto">
						<p class="small text-muted me-auto" id="hotelResultsInfo">1 - <span id="visibleHotelCount"><?= $hotelCount ?></span> of <?= $hotelCount ?> Hotels</p>
					</div>

					<div class="col-lg-3">
						<div class="mb-3">
							<select class="form-select" id="sortSelect" aria-label="Sort hotels">
								<option value="" selected>Sort by: Our top picks</option>
								<option value="price_asc">Price: Low to High</option>
								<option value="price_desc">Price: High to Low</option>
								<option value="rating_desc">Rating: High to Low</option>
								<option value="star_desc">Star Rating: High to Low</option>
							</select>
						</div>
					</div>
				</div>


				<?php if (!empty($hotels)): ?>
					<?php
					// Parse guest info for display
					$guestsArr = explode(',', $defaults->guests ?? '1,0,1');
					$displayAdults = isset($guestsArr[0]) ? (int)$guestsArr[0] : 1;
					$displayChildren = isset($guestsArr[1]) ? (int)$guestsArr[1] : 0;
					$displayRooms = isset($guestsArr[2]) ? (int)$guestsArr[2] : 1;

					// Calculate number of nights
					$arrivalDt = DateTime::createFromFormat('Y-m-d', $arrivalFormatted);
					$departureDt = DateTime::createFromFormat('Y-m-d', $departureFormatted);
					$nights = ($arrivalDt && $departureDt) ? $arrivalDt->diff($departureDt)->days : 1;
					$nightText = $nights > 1 ? 'Nights' : 'Night';

					?>
					<?php foreach ($hotels as $hotel): ?>
						<?php if (isset($hotel->Hotelwiseroomcount) && $hotel->Hotelwiseroomcount > 0): ?>
							<?php
							// Get first room detail for display
							$roomDetail = null;
							if (isset($hotel->RoomDetails->RoomDetail)) {
								$roomDetails = $hotel->RoomDetails->RoomDetail;
								$roomDetail = is_array($roomDetails) ? $roomDetails[0] : $roomDetails;
							}

							// Calculate estimated taxes (5% of price) - convert to Naira
							$hotelPriceUsd = isset($hotel->Price) ? (float)$hotel->Price : 0;
							$hotelPrice = convertToNaira($hotelPriceUsd, $apiCurrency);
							$taxAmount = $hotelPrice * 0.05;

							// Get rating label (scale 0-5)
							$rating = isset($hotel->Rating) ? (float)$hotel->Rating : 0;
							if ($rating >= 4.5) {
								$ratingLabel = 'Exceptional';
							} elseif ($rating >= 4) {
								$ratingLabel = 'Excellent';
							} elseif ($rating >= 3.5) {
								$ratingLabel = 'Very Good';
							} elseif ($rating >= 3) {
								$ratingLabel = 'Good';
							} else {
								$ratingLabel = 'Pleasant';
							}
							?>
							<div class="card border-0 mt-4 hotel-card"
							 data-aos="fade-up"
							 data-aos-duration="1000"
							 data-price="<?= $hotelPrice ?>"
							 data-star-rating="<?= isset($hotel->Rating) ? (int)$hotel->Rating : 0 ?>"
							 data-rating="<?= isset($hotel->Rating) ? (float)$hotel->Rating : 0 ?>"
							 data-name="<?= isset($hotel->Name) ? htmlspecialchars(strtolower($hotel->Name)) : '' ?>">
								<div class="card-body">
									<div class="row g-3">
										<div class="col-lg-4">
											<img src="<?= isset($hotel->ThumbImages) ? $hotel->ThumbImages : base_url('assets/images/hotel/hotel-1.png') ?>"
												 width="100%" class="img-fluid" alt="<?= isset($hotel->Name) ? htmlspecialchars($hotel->Name) : 'Hotel' ?>">
										</div>
										<div class="col-lg-8">
											<div class="row g-3">
												<div class="col-lg-12">
													<div class="row">
														<div class="col-lg-8 col-6">
															<h5 class="fw-bold mb-n0">
																<?= isset($hotel->Name) ? htmlspecialchars($hotel->Name) : 'Hotel' ?>
															</h5>
															<?php if (isset($hotel->StarRating) && $hotel->StarRating > 0): ?>
																<?php for ($i = 0; $i < (int)$hotel->StarRating; $i++): ?>
																	<i class="ri-star-fill text-warning"></i>
																<?php endfor; ?>
															<?php else: ?>
																<img src="<?= base_url('assets/images/hotel/rating.svg') ?>" class="img-fluid" alt="">
															<?php endif; ?>
														</div>
														<div class="col-lg-4 col-6 d-flex">
															<div class="col-8">
																<h6 class="fw-bold"><?= $ratingLabel ?></h6>
																<p class="small"><?= isset($hotel->ReviewCount) ? number_format($hotel->ReviewCount) : '' ?> reviews</p>
															</div>
															<div class="col-4">
																<span class="badge bg-warning p-3"><?= isset($hotel->Rating) ? $hotel->Rating : 'N/A' ?></span>
															</div>
														</div>
													</div>

													<div class="row">
														<div class="mt-2">
															<a href="#" class="me-1 text-decoration-underline"><?= htmlspecialchars($defaults->location) ?></a> &bull;
																<a href="#mapSection" class="mx-1 text-decoration-underline" onclick="document.getElementById('mapSection').scrollIntoView({ behavior: 'smooth' }); return false;">Show on map</a>
															&bull;
															<span class="small mx-1"><?= isset($hotel->DistanceFromCenter) ? $hotel->DistanceFromCenter : '' ?> from center</span>
														</div>
													</div>

													<div class="row g-3 mt-2 align-items-end">
														<div class="col-lg-7 lh-1">
															<?php if ($roomDetail): ?>
																<?php
																$roomTypeParts = isset($roomDetail->Type) ? explode(',', $roomDetail->Type) : ['Standard Room'];
																?>
																<p class=""><?= htmlspecialchars($roomTypeParts[0]) ?></p>
																<?php if (isset($roomTypeParts[1])): ?>
																	<p><?= htmlspecialchars($roomTypeParts[1]) ?></p>
																<?php endif; ?>
																<?php if (isset($roomDetail->BoardBasis)): ?>
																	<p class="text-success fw-bold"><?= htmlspecialchars($roomDetail->BoardBasis) ?></p>
																<?php endif; ?>
																<?php if (isset($roomDetail->RoomDescription)): ?>
																	<p class="text-success fw-bold"><?= htmlspecialchars($roomDetail->RoomDescription) ?></p>
																<?php endif; ?>
															<?php else: ?>
																<p>Standard Room</p>
															<?php endif; ?>
														</div>
														<div class="col-lg-5 text-lg-end">
															<p><?= $nights ?> <?= $nightText ?>, <?= $displayAdults ?> Adult<?= $displayAdults > 1 ? 's' : '' ?></p>
															<h5 class="text-success fw-bold">
																<?= DISPLAY_CURRENCY_SYMBOL ?> <?= number_format($hotelPrice, 2) ?>
															</h5>
															<p class="small">+ <?= DISPLAY_CURRENCY_SYMBOL ?> <?= number_format($taxAmount, 2) ?> taxes and charges</p>
															<form action="<?= site_url('hotel/index') ?>" method="post">
																<input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>"
																	   value="<?= $this->security->get_csrf_hash(); ?>" />
																<input type="hidden" name="hotelId" value="<?= isset($hotel->Id) ? $hotel->Id : '' ?>">
																<input type="hidden" name="hotelName" value="<?= isset($hotel->Name) ? htmlspecialchars($hotel->Name) : '' ?>">
																<input type="hidden" name="arrival" value="<?= $arrivalFormatted ?>">
																<input type="hidden" name="departure" value="<?= $departureFormatted ?>">
																<input type="hidden" name="guestData" value="<?= $defaults->guests ?>">
																<button type="submit" class="btn btn-primary">Book now</button>
															</form>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						<?php endif; ?>
					<?php endforeach; ?>

				<?php else: ?>
					<p class="text-center mt-4" id="noHotelsMessage">No hotels found. Try adjusting your search criteria.</p>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>

<script>
$(document).ready(function() {
	// Hotel filtering and sorting functionality
	const hotelCards = $('.hotel-card');
	const totalHotels = hotelCards.length;

	// Initialize filter counts
	function updateFilterCounts() {
		// Update total hotel count for all amenity filters
		$('.total-hotel-count').text(totalHotels);

		// Update price filter counts
		$('.filter-price').each(function() {
			const min = parseFloat($(this).data('min'));
			const max = parseFloat($(this).data('max'));
			let count = 0;
			hotelCards.each(function() {
				const price = parseFloat($(this).data('price'));
				if (price >= min && price < max) {
					count++;
				}
			});
			$('.filter-count[data-filter="' + $(this).attr('id') + '"]').text(count);
		});

		// Update star rating filter counts
		$('.filter-star').each(function() {
			const star = parseInt($(this).data('star'));
			let count = 0;
			hotelCards.each(function() {
				const hotelStar = parseInt($(this).data('starRating'));
				if (hotelStar === star) {
					count++;
				}
			});
			$('.filter-count[data-filter="' + $(this).attr('id') + '"]').text(count);
		});
	}

	// Apply filters
	function applyFilters() {
		const selectedPrices = [];
		const selectedStars = [];

		// Get selected price ranges
		$('.filter-price:checked').each(function() {
			selectedPrices.push({
				min: parseFloat($(this).data('min')),
				max: parseFloat($(this).data('max'))
			});
		});

		// Get selected star ratings
		$('.filter-star:checked').each(function() {
			selectedStars.push(parseInt($(this).data('star')));
		});

		let visibleCount = 0;

		hotelCards.each(function() {
			const card = $(this);
			const price = parseFloat(card.data('price'));
			const starRating = parseInt(card.data('starRating'));

			let showByPrice = true;
			let showByStar = true;

			// Check price filter
			if (selectedPrices.length > 0) {
				showByPrice = false;
				for (const range of selectedPrices) {
					if (price >= range.min && price < range.max) {
						showByPrice = true;
						break;
					}
				}
			}

			// Check star filter
			if (selectedStars.length > 0) {
				showByStar = selectedStars.includes(starRating);
			}

			// Show/hide based on filters
			if (showByPrice && showByStar) {
				card.show();
				visibleCount++;
			} else {
				card.hide();
			}
		});

		// Update visible count
		$('#visibleHotelCount').text(visibleCount);

		// Show/hide no results message
		if (visibleCount === 0 && totalHotels > 0) {
			if ($('#noFilterResults').length === 0) {
				$('.hotel-card').first().before('<p class="text-center mt-4" id="noFilterResults">No hotels match your filter criteria. Try adjusting your filters.</p>');
			}
			$('#noFilterResults').show();
		} else {
			$('#noFilterResults').hide();
		}
	}

	// Sort hotels
	function sortHotels(sortBy) {
		const container = hotelCards.parent();
		const cards = hotelCards.toArray();

		cards.sort(function(a, b) {
			const priceA = parseFloat($(a).data('price'));
			const priceB = parseFloat($(b).data('price'));
			const ratingA = parseFloat($(a).data('rating'));
			const ratingB = parseFloat($(b).data('rating'));
			const starA = parseInt($(a).data('starRating'));
			const starB = parseInt($(b).data('starRating'));

			switch(sortBy) {
				case 'price_asc':
					return priceA - priceB;
				case 'price_desc':
					return priceB - priceA;
				case 'rating_desc':
					return ratingB - ratingA;
				case 'star_desc':
					return starB - starA;
				default:
					return 0;
			}
		});

		// Reorder DOM
		cards.forEach(function(card) {
			container.append(card);
		});
	}

	// Event listeners
	$('.filter-price, .filter-star').on('change', function() {
		applyFilters();
	});

	// Amenity filters - show all hotels (these are placeholder filters)
	$('.filter-amenity').on('change', function() {
		// These filters don't have API data, so they just show all hotels
		// Uncheck if trying to filter (to indicate no data available)
		// Or simply apply the current price/star filters
		applyFilters();
	});

	$('#sortSelect').on('change', function() {
		sortHotels($(this).val());
	});

	// Initialize
	updateFilterCounts();
});
</script>
