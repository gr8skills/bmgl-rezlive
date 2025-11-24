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

				<div class="card border-0 mt-4 d-none d-lg-block">
					<div class="card-header bg-white text-primary" data-aos="fade-up" data-aos-duration="1000">
						<h5 class="fw-bold">Filter by:</h5>
					</div>
					<div class="card-body">
						<h6 class="fw-bold mb-3" data-aos="fade-up" data-aos-duration="1000">Your budget (per night)
						</h6>
						<form action="" data-aos="fade-up" data-aos-duration="1000">
							<div class="d-flex" data-aos="fade-up" data-aos-duration="1000">
								<div class="form-check me-auto">
									<input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
									<label class="form-check-label">
										0 - NGN 20,000
									</label>
								</div>
								<span class="ms-auto">2</span>
							</div>

							<div class="d-flex mt-3" data-aos="fade-up" data-aos-duration="1000">
								<div class="form-check me-auto">
									<input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
									<label class="form-check-label">
										NGN 20,000 - NGN40,000
									</label>
								</div>
								<span class="ms-auto">29</span>
							</div>

							<div class="d-flex mt-3" data-aos="fade-up" data-aos-duration="1000">
								<div class="form-check me-auto">
									<input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
									<label class="form-check-label">
										NGN40,000 - NGN 60,000
									</label>
								</div>
								<span class="ms-auto">86</span>
							</div>

							<div class="d-flex mt-3" data-aos="fade-up" data-aos-duration="1000">
								<div class="form-check me-auto">
									<input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
									<label class="form-check-label">
										NGN 60,000 - NGN80,000
									</label>
								</div>
								<span class="ms-auto">102</span>
							</div>

							<div class="d-flex mt-3">
								<div class="form-check me-auto">
									<input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
									<label class="form-check-label">
										NGN80,000 - NGN100,000
									</label>
								</div>
								<span class="ms-auto">94</span>
							</div>

							<div class="d-flex mt-3" data-aos="fade-up" data-aos-duration="1000">
								<div class="form-check me-auto">
									<input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
									<label class="form-check-label">
										NGN100,000 +
									</label>
								</div>
								<span class="ms-auto">23</span>
							</div>
							<hr>
							<div class="" data-aos="fade-up" data-aos-duration="1000">
								<h6 class="fw-bold">Health and safety</h6>
								<div class="d-flex">
									<div class="form-check me-auto">
										<input class="form-check-input" type="checkbox" value=""
											   id="flexCheckDefault">
										<label class="form-check-label">
											Hotels that take health and safety measures
										</label>
									</div>
									<span class="ms-auto">107</span>
								</div>
							</div>
							<hr>
							<h6 class="fw-bold mb-3" data-aos="fade-up" data-aos-duration="1000">Popular filters
							</h6>
							<div class="d-flex" data-aos="fade-up" data-aos-duration="1000">
								<div class="form-check me-auto">
									<input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
									<label class="form-check-label">
										Hotels
									</label>
								</div>
								<span class="ms-auto">200</span>
							</div>

							<div class="d-flex mt-3" data-aos="fade-up" data-aos-duration="1000">
								<div class="form-check me-auto">
									<input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
									<label class="form-check-label">
										Indoor pool
									</label>
								</div>
								<span class="ms-auto">59</span>
							</div>

							<div class="d-flex mt-3" data-aos="fade-up" data-aos-duration="1000">
								<div class="form-check me-auto">
									<input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
									<label class="form-check-label">
										Bed and breakfast
									</label>
								</div>
								<span class="ms-auto">100</span>
							</div>

							<div class="d-flex mt-3" data-aos="fade-up" data-aos-duration="1000">
								<div class="form-check me-auto">
									<input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
									<label class="form-check-label">
										Hot tub/ Jacuzzi
									</label>
								</div>
								<span class="ms-auto">40</span>
							</div>

							<div class="d-flex mt-3" data-aos="fade-up" data-aos-duration="1000">
								<div class="form-check me-auto">
									<input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
									<label class="form-check-label">
										Hostels
									</label>
								</div>
								<span class="ms-auto">22</span>
							</div>

							<div class="d-flex mt-3" data-aos="fade-up" data-aos-duration="1000">
								<div class="form-check me-auto">
									<input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
									<label class="form-check-label">
										Playground
									</label>
								</div>
								<span class="ms-auto">29</span>
							</div>

							<div class="d-flex mt-3" data-aos="fade-up" data-aos-duration="1000">
								<div class="form-check me-auto">
									<input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
									<label class="form-check-label">
										Spa
									</label>
								</div>
								<span class="ms-auto">146</span>
							</div>

							<div class="d-flex mt-3" data-aos="fade-up" data-aos-duration="1000">
								<div class="form-check me-auto">
									<input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
									<label class="form-check-label">
										Villas
									</label>
								</div>
								<span class="ms-auto">79</span>
							</div>
							<hr>
							<div class="" data-aos="fade-up" data-aos-duration="1000">
								<h6 class="fw-bold">Sustainability</h6>
								<div class="d-flex">
									<div class="form-check me-auto">
										<input class="form-check-input" type="checkbox" value=""
											   id="flexCheckDefault">
										<label class="form-check-label">
											Travel sustainability properties
										</label>
									</div>
									<span class="ms-auto">200</span>
								</div>
							</div>
							<hr>
							<h6 class="fw-bold mb-3" data-aos="fade-up" data-aos-duration="1000">Sustainability</h6>
							<div class="d-flex" data-aos="fade-up" data-aos-duration="1000">
								<div class="form-check me-auto">
									<input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
									<label class="form-check-label">
										1 Star
									</label>
								</div>
								<span class="ms-auto">5</span>
							</div>

							<div class="d-flex mt-3" data-aos="fade-up" data-aos-duration="1000">
								<div class="form-check me-auto">
									<input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
									<label class="form-check-label">
										2 Star
									</label>
								</div>
								<span class="ms-auto">30</span>
							</div>

							<div class="d-flex mt-3" data-aos="fade-up" data-aos-duration="1000">
								<div class="form-check me-auto">
									<input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
									<label class="form-check-label">
										3 Star
									</label>
								</div>
								<span class="ms-auto">96</span>
							</div>

							<div class="d-flex mt-3" data-aos="fade-up" data-aos-duration="1000">
								<div class="form-check me-auto">
									<input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
									<label class="form-check-label">
										4 Star
									</label>
								</div>
								<span class="ms-auto">58</span>
							</div>

							<div class="d-flex mt-3" data-aos="fade-up" data-aos-duration="1000">
								<div class="form-check me-auto">
									<input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
									<label class="form-check-label">
										Unrated
									</label>
								</div>
								<span class="ms-auto">20</span>
							</div>
							<hr>

							<div class="" data-aos="fade-up" data-aos-duration="1000">
								<h6 class="fw-bold" data-aos="fade-up" data-aos-duration="1000">Distance from the
									centre of Accra</h6>
								<div class="d-flex" data-aos="fade-up" data-aos-duration="1000">
									<div class="form-check me-auto">
										<input class="form-check-input" type="checkbox" value=""
											   id="flexCheckDefault">
										<label class="form-check-label">
											Less than 1km
										</label>
									</div>
									<span class="ms-auto">5</span>
								</div>
								<div class="d-flex mt-3" data-aos="fade-up" data-aos-duration="1000">
									<div class="form-check me-auto">
										<input class="form-check-input" type="checkbox" value=""
											   id="flexCheckDefault">
										<label class="form-check-label">
											Less than 3km
										</label>
									</div>
									<span class="ms-auto">17</span>
								</div>
								<div class="d-flex mt-3" data-aos="fade-up" data-aos-duration="1000">
									<div class="form-check me-auto">
										<input class="form-check-input" type="checkbox" value=""
											   id="flexCheckDefault">
										<label class="form-check-label">
											Less than 5km
										</label>
									</div>
									<span class="ms-auto">30</span>
								</div>
							</div>

							<hr>
							<h6 class="fw-bold mb-3" data-aos="fade-up" data-aos-duration="1000">Fun things to do
							</h6>
							<div class="d-flex" data-aos="fade-up" data-aos-duration="1000">
								<div class="form-check me-auto">
									<input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
									<label class="form-check-label">
										Fitness center
									</label>
								</div>
								<span class="ms-auto">5</span>
							</div>

							<div class="d-flex mt-3" data-aos="fade-up" data-aos-duration="1000">
								<div class="form-check me-auto">
									<input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
									<label class="form-check-label">
										Indoor pool
									</label>
								</div>
								<span class="ms-auto">17</span>
							</div>

							<div class="d-flex mt-3" data-aos="fade-up" data-aos-duration="1000">
								<div class="form-check me-auto">
									<input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
									<label class="form-check-label">
										Golf course
									</label>
								</div>
								<span class="ms-auto">3</span>
							</div>

							<div class="d-flex mt-3" data-aos="fade-up" data-aos-duration="1000">
								<div class="form-check me-auto">
									<input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
									<label class="form-check-label">
										Hot tub / Jacuzzi
									</label>
								</div>
								<span class="ms-auto">15</span>
							</div>

							<div class="d-flex mt-3" data-aos="fade-up" data-aos-duration="1000">
								<div class="form-check me-auto">
									<input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
									<label class="form-check-label">
										Happy hour
									</label>
								</div>
								<span class="ms-auto">30</span>
							</div>

							<div class="d-flex mt-3" data-aos="fade-up" data-aos-duration="1000">
								<div class="form-check me-auto">
									<input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
									<label class="form-check-label">
										Game room
									</label>
								</div>
								<span class="ms-auto">30</span>
							</div>

							<div class="d-flex mt-3" data-aos="fade-up" data-aos-duration="1000">
								<div class="form-check me-auto">
									<input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
									<label class="form-check-label">
										Live sports events
									</label>
								</div>
								<span class="ms-auto">26</span>
							</div>

							<div class="d-flex mt-3" data-aos="fade-up" data-aos-duration="1000">
								<div class="form-check me-auto">
									<input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
									<label class="form-check-label">
										Mini golf
									</label>
								</div>
								<span class="ms-auto">79</span>
							</div>

							<div class="d-flex mt-3" data-aos="fade-up" data-aos-duration="1000">
								<div class="form-check me-auto">
									<input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
									<label class="form-check-label">
										Spa facilities
									</label>
								</div>
								<span class="ms-auto">14</span>
							</div>

							<div class="d-flex mt-3" data-aos="fade-up" data-aos-duration="1000">
								<div class="form-check me-auto">
									<input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
									<label class="form-check-label">
										Stand-up comedy
									</label>
								</div>
								<span class="ms-auto">5</span>
							</div>

							<div class="d-flex mt-3" data-aos="fade-up" data-aos-duration="1000">
								<div class="form-check me-auto">
									<input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
									<label class="form-check-label">
										Sauna
									</label>
								</div>
								<span class="ms-auto">28</span>
							</div>

							<div class="d-flex mt-3" data-aos="fade-up" data-aos-duration="1000">
								<div class="form-check me-auto">
									<input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
									<label class="form-check-label">
										Playground
									</label>
								</div>
								<span class="ms-auto">5</span>
							</div>

							<div class="d-flex mt-3" data-aos="fade-up" data-aos-duration="1000">
								<div class="form-check me-auto">
									<input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
									<label class="form-check-label">
										Canoeing
									</label>
								</div>
								<span class="ms-auto">49</span>
							</div>

							<div class="d-flex mt-3" data-aos="fade-up" data-aos-duration="1000">
								<div class="form-check me-auto">
									<input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
									<label class="form-check-label">
										Beauty service
									</label>
								</div>
								<span class="ms-auto">33</span>
							</div>

							<div class="d-flex mt-3" data-aos="fade-up" data-aos-duration="1000">
								<div class="form-check me-auto">
									<input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
									<label class="form-check-label">
										Spa lounge / Relation area
									</label>
								</div>
								<span class="ms-auto">5</span>
							</div>

							<div class="d-flex mt-3" data-aos="fade-up" data-aos-duration="1000">
								<div class="form-check me-auto">
									<input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
									<label class="form-check-label">
										Bar
									</label>
								</div>
								<span class="ms-auto">5</span>
							</div>

							<div class="d-flex mt-3" data-aos="fade-up" data-aos-duration="1000">
								<div class="form-check me-auto">
									<input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
									<label class="form-check-label">
										Movie night
									</label>
								</div>
								<span class="ms-auto">5</span>
							</div>

							<div class="d-flex mt-3" data-aos="fade-up" data-aos-duration="1000">
								<div class="form-check me-auto">
									<input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
									<label class="form-check-label">
										Walking tour
									</label>
								</div>
								<span class="ms-auto">5</span>
							</div>

							<div class="d-flex mt-3" data-aos="fade-up" data-aos-duration="1000">
								<div class="form-check me-auto">
									<input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
									<label class="form-check-label">
										Bike tour
									</label>
								</div>
								<span class="ms-auto">5</span>
							</div>

							<div class="d-flex mt-3" data-aos="fade-up" data-aos-duration="1000">
								<div class="form-check me-auto">
									<input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
									<label class="form-check-label">
										Themed dinner
									</label>
								</div>
								<span class="ms-auto">5</span>
							</div>
							<hr>

							<div class="" data-aos="fade-up" data-aos-duration="1000">
								<h6 class="fw-bold" data-aos="fade-up" data-aos-duration="1000">Landmarks</h6>
								<div class="d-flex" data-aos="fade-up" data-aos-duration="1000">
									<div class="form-check me-auto">
										<input class="form-check-input" type="checkbox" value=""
											   id="flexCheckDefault">
										<label class="form-check-label">
											City center
										</label>
									</div>
									<span class="ms-auto">31</span>
								</div>

								<div class="d-flex mt-3" data-aos="fade-up" data-aos-duration="1000">
									<div class="form-check me-auto">
										<input class="form-check-input" type="checkbox" value=""
											   id="flexCheckDefault">
										<label class="form-check-label">
											Nationwide arena
										</label>
									</div>
									<span class="ms-auto">31</span>
								</div>
							</div>
							<hr>
							<h6 class="fw-bold mb-3" data-aos="fade-up" data-aos-duration="1000">Review Score based
								on guests reviews</h6>
							<div class="d-flex" data-aos="fade-up" data-aos-duration="1000">
								<div class="form-check me-auto">
									<input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
									<label class="form-check-label">
										Wonderful:9+
									</label>
								</div>
								<span class="ms-auto">22</span>
							</div>

							<div class="d-flex mt-3" data-aos="fade-up" data-aos-duration="1000">
								<div class="form-check me-auto">
									<input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
									<label class="form-check-label">
										Very Good:8+
									</label>
								</div>
								<span class="ms-auto">132</span>
							</div>

							<div class="d-flex mt-3" data-aos="fade-up" data-aos-duration="1000">
								<div class="form-check me-auto">
									<input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
									<label class="form-check-label">
										Good:7+
									</label>
								</div>
								<span class="ms-auto">177</span>
							</div>

							<div class="d-flex mt-3" data-aos="fade-up" data-aos-duration="1000">
								<div class="form-check me-auto">
									<input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
									<label class="form-check-label">
										Pleasant:6+
									</label>
								</div>
								<span class="ms-auto">98</span>
							</div>
							<hr>

							<div class="" data-aos="fade-up" data-aos-duration="1000">
								<h6 class="fw-bold mb-3" data-aos="fade-up" data-aos-duration="1000">Room facilities
								</h6>
								<div class="d-flex" data-aos="fade-up" data-aos-duration="1000">
									<div class="form-check me-auto">
										<input class="form-check-input" type="checkbox" value=""
											   id="flexCheckDefault">
										<label class="form-check-label">
											Kitchen
										</label>
									</div>
									<span class="ms-auto">22</span>
								</div>

								<div class="d-flex mt-3" data-aos="fade-up" data-aos-duration="1000">
									<div class="form-check me-auto">
										<input class="form-check-input" type="checkbox" value=""
											   id="flexCheckDefault">
										<label class="form-check-label">
											Air conditioning
										</label>
									</div>
									<span class="ms-auto">22</span>
								</div>

								<div class="d-flex mt-3" data-aos="fade-up" data-aos-duration="1000">
									<div class="form-check me-auto">
										<input class="form-check-input" type="checkbox" value=""
											   id="flexCheckDefault">
										<label class="form-check-label">
											Desk
										</label>
									</div>
									<span class="ms-auto">177</span>
								</div>

								<div class="d-flex mt-3" data-aos="fade-up" data-aos-duration="1000">
									<div class="form-check me-auto">
										<input class="form-check-input" type="checkbox" value=""
											   id="flexCheckDefault">
										<label class="form-check-label">
											Bath tub
										</label>
									</div>
									<span class="ms-auto">98</span>
								</div>

								<div class="d-flex mt-3" data-aos="fade-up" data-aos-duration="1000">
									<div class="form-check me-auto">
										<input class="form-check-input" type="checkbox" value=""
											   id="flexCheckDefault">
										<label class="form-check-label">
											Flat screen TV
										</label>
									</div>
									<span class="ms-auto">98</span>
								</div>

								<div class="d-flex mt-3" data-aos="fade-up" data-aos-duration="1000">
									<div class="form-check me-auto">
										<input class="form-check-input" type="checkbox" value=""
											   id="flexCheckDefault">
										<label class="form-check-label">
											Cofee / Tea maker
										</label>
									</div>
									<span class="ms-auto">98</span>
								</div>
							</div>
							<hr>

							<div class="" data-aos="fade-up" data-aos-duration="1000">
								<h6 class="fw-bold mb-3" data-aos="fade-up" data-aos-duration="1000">Facilities</h6>
								<div class="d-flex" data-aos="fade-up" data-aos-duration="1000">
									<div class="form-check me-auto">
										<input class="form-check-input" type="checkbox" value=""
											   id="flexCheckDefault">
										<label class="form-check-label">
											Non-smoking rooms
										</label>
									</div>
									<span class="ms-auto">22</span>
								</div>

								<div class="d-flex mt-3" data-aos="fade-up" data-aos-duration="1000">
									<div class="form-check me-auto">
										<input class="form-check-input" type="checkbox" value=""
											   id="flexCheckDefault">
										<label class="form-check-label">
											Parking
										</label>
									</div>
									<span class="ms-auto">22</span>
								</div>

								<div class="d-flex mt-3" data-aos="fade-up" data-aos-duration="1000">
									<div class="form-check me-auto">
										<input class="form-check-input" type="checkbox" value=""
											   id="flexCheckDefault">
										<label class="form-check-label">
											24-hour front desk
										</label>
									</div>
									<span class="ms-auto">177</span>
								</div>

								<div class="d-flex mt-3" data-aos="fade-up" data-aos-duration="1000">
									<div class="form-check me-auto">
										<input class="form-check-input" type="checkbox" value=""
											   id="flexCheckDefault">
										<label class="form-check-label">
											Free Wi-fi
										</label>
									</div>
									<span class="ms-auto">98</span>
								</div>

								<div class="d-flex mt-3" data-aos="fade-up" data-aos-duration="1000">
									<div class="form-check me-auto">
										<input class="form-check-input" type="checkbox" value=""
											   id="flexCheckDefault">
										<label class="form-check-label">
											Restaurant
										</label>
									</div>
									<span class="ms-auto">98</span>
								</div>

								<div class="d-flex mt-3" data-aos="fade-up" data-aos-duration="1000">
									<div class="form-check me-auto">
										<input class="form-check-input" type="checkbox" value=""
											   id="flexCheckDefault">
										<label class="form-check-label">
											Pet friendly
										</label>
									</div>
									<span class="ms-auto">98</span>
								</div>

								<div class="d-flex mt-3" data-aos="fade-up" data-aos-duration="1000">
									<div class="form-check me-auto">
										<input class="form-check-input" type="checkbox" value=""
											   id="flexCheckDefault">
										<label class="form-check-label">
											Room service
										</label>
									</div>
									<span class="ms-auto">98</span>
								</div>

								<div class="d-flex mt-3" data-aos="fade-up" data-aos-duration="1000">
									<div class="form-check me-auto">
										<input class="form-check-input" type="checkbox" value=""
											   id="flexCheckDefault">
										<label class="form-check-label">
											Fitness center
										</label>
									</div>
									<span class="ms-auto">98</span>
								</div>

								<div class="d-flex mt-3" data-aos="fade-up" data-aos-duration="1000">
									<div class="form-check me-auto">
										<input class="form-check-input" type="checkbox" value=""
											   id="flexCheckDefault">
										<label class="form-check-label">
											Airport shuttle
										</label>
									</div>
									<span class="ms-auto">98</span>
								</div>

								<div class="d-flex mt-3" data-aos="fade-up" data-aos-duration="1000">
									<div class="form-check me-auto">
										<input class="form-check-input" type="checkbox" value=""
											   id="flexCheckDefault">
										<label class="form-check-label">
											Facilities for disabled guests
										</label>
									</div>
									<span class="ms-auto">98</span>
								</div>

								<div class="d-flex mt-3" data-aos="fade-up" data-aos-duration="1000">
									<div class="form-check me-auto">
										<input class="form-check-input" type="checkbox" value=""
											   id="flexCheckDefault">
										<label class="form-check-label">
											Family room
										</label>
									</div>
									<span class="ms-auto">98</span>
								</div>

								<div class="d-flex mt-3" data-aos="fade-up" data-aos-duration="1000">
									<div class="form-check me-auto">
										<input class="form-check-input" type="checkbox" value=""
											   id="flexCheckDefault">
										<label class="form-check-label">
											Spa
										</label>
									</div>
									<span class="ms-auto">98</span>
								</div>

								<div class="d-flex mt-3" data-aos="fade-up" data-aos-duration="1000">
									<div class="form-check me-auto">
										<input class="form-check-input" type="checkbox" value=""
											   id="flexCheckDefault">
										<label class="form-check-label">
											Electronic charging room
										</label>
									</div>
									<span class="ms-auto">98</span>
								</div>

								<div class="d-flex mt-3">
									<div class="form-check me-auto">
										<input class="form-check-input" type="checkbox" value=""
											   id="flexCheckDefault">
										<label class="form-check-label">
											Swimming pool
										</label>
									</div>
									<span class="ms-auto">98</span>
								</div>
							</div>
							<hr>

							<div class="" data-aos="fade-up" data-aos-duration="1000">
								<h6 class="fw-bold mb-3" data-aos="fade-up" data-aos-duration="1000">Property
									Accessibility</h6>
								<div class="d-flex" data-aos="fade-up" data-aos-duration="1000">
									<div class="form-check me-auto">
										<input class="form-check-input" type="checkbox" value=""
											   id="flexCheckDefault">
										<label class="form-check-label">
											Wheelchair accessible
										</label>
									</div>
									<span class="ms-auto">22</span>
								</div>

								<div class="d-flex mt-3" data-aos="fade-up" data-aos-duration="1000">
									<div class="form-check me-auto">
										<input class="form-check-input" type="checkbox" value=""
											   id="flexCheckDefault">
										<label class="form-check-label">
											Toilet with grab rails
										</label>
									</div>
									<span class="ms-auto">22</span>
								</div>

								<div class="d-flex mt-3" data-aos="fade-up" data-aos-duration="1000">
									<div class="form-check me-auto">
										<input class="form-check-input" type="checkbox" value=""
											   id="flexCheckDefault">
										<label class="form-check-label">
											Raised toilet
										</label>
									</div>
									<span class="ms-auto">177</span>
								</div>

								<div class="d-flex mt-3" data-aos="fade-up" data-aos-duration="1000">
									<div class="form-check me-auto">
										<input class="form-check-input" type="checkbox" value=""
											   id="flexCheckDefault">
										<label class="form-check-label">
											Lowered sink
										</label>
									</div>
									<span class="ms-auto">98</span>
								</div>

								<div class="d-flex mt-3" data-aos="fade-up" data-aos-duration="1000">
									<div class="form-check me-auto">
										<input class="form-check-input" type="checkbox" value=""
											   id="flexCheckDefault">
										<label class="form-check-label">
											Bathroom emergency cord
										</label>
									</div>
									<span class="ms-auto">98</span>
								</div>

								<div class="d-flex mt-3" data-aos="fade-up" data-aos-duration="1000">
									<div class="form-check me-auto">
										<input class="form-check-input" type="checkbox" value=""
											   id="flexCheckDefault">
										<label class="form-check-label">
											Visual aids (Braille)
										</label>
									</div>
									<span class="ms-auto">98</span>
								</div>

								<div class="d-flex mt-3" data-aos="fade-up" data-aos-duration="1000">
									<div class="form-check me-auto">
										<input class="form-check-input" type="checkbox" value=""
											   id="flexCheckDefault">
										<label class="form-check-label">
											Visual aids (Tactile signs)
										</label>
									</div>
									<span class="ms-auto">98</span>
								</div>

								<div class="d-flex mt-3" data-aos="fade-up" data-aos-duration="1000">
									<div class="form-check me-auto">
										<input class="form-check-input" type="checkbox" value=""
											   id="flexCheckDefault">
										<label class="form-check-label">
											Auditory guidance
										</label>
									</div>
									<span class="ms-auto">98</span>
								</div>
							</div>
							<hr>

							<div class="" data-aos="fade-up" data-aos-duration="1000">
								<h6 class="fw-bold mb-3" data-aos="fade-up" data-aos-duration="1000">Room
									Accessibility</h6>
								<div class="d-flex" data-aos="fade-up" data-aos-duration="1000">
									<div class="form-check me-auto">
										<input class="form-check-input" type="checkbox" value=""
											   id="flexCheckDefault">
										<label class="form-check-label">
											Shower chair
										</label>
									</div>
									<span class="ms-auto">22</span>
								</div>

								<div class="d-flex mt-3" data-aos="fade-up" data-aos-duration="1000">
									<div class="form-check me-auto">
										<input class="form-check-input" type="checkbox" value=""
											   id="flexCheckDefault">
										<label class="form-check-label">
											Emergency cord in bathroom
										</label>
									</div>
									<span class="ms-auto">22</span>
								</div>

								<div class="d-flex mt-3" data-aos="fade-up" data-aos-duration="1000">
									<div class="form-check me-auto">
										<input class="form-check-input" type="checkbox" value=""
											   id="flexCheckDefault">
										<label class="form-check-label">
											Lower sink
										</label>
									</div>
									<span class="ms-auto">177</span>
								</div>

								<div class="d-flex mt-3" data-aos="fade-up" data-aos-duration="1000">
									<div class="form-check me-auto">
										<input class="form-check-input" type="checkbox" value=""
											   id="flexCheckDefault">
										<label class="form-check-label">
											Raised toilet
										</label>
									</div>
									<span class="ms-auto">98</span>
								</div>

								<div class="d-flex mt-3" data-aos="fade-up" data-aos-duration="1000">
									<div class="form-check me-auto">
										<input class="form-check-input" type="checkbox" value=""
											   id="flexCheckDefault">
										<label class="form-check-label">
											Walk-in shower
										</label>
									</div>
									<span class="ms-auto">98</span>
								</div>

								<div class="d-flex mt-3" data-aos="fade-up" data-aos-duration="1000">
									<div class="form-check me-auto">
										<input class="form-check-input" type="checkbox" value=""
											   id="flexCheckDefault">
										<label class="form-check-label">
											Roll-in shower
										</label>
									</div>
									<span class="ms-auto">98</span>
								</div>

								<div class="d-flex mt-3" data-aos="fade-up" data-aos-duration="1000">
									<div class="form-check me-auto">
										<input class="form-check-input" type="checkbox" value=""
											   id="flexCheckDefault">
										<label class="form-check-label">
											Adapted bath
										</label>
									</div>
									<span class="ms-auto">98</span>
								</div>

								<div class="d-flex mt-3" data-aos="fade-up" data-aos-duration="1000">
									<div class="form-check me-auto">
										<input class="form-check-input" type="checkbox" value=""
											   id="flexCheckDefault">
										<label class="form-check-label">
											Toilet grab with rails
										</label>
									</div>
									<span class="ms-auto">98</span>
								</div>

								<div class="d-flex mt-3" data-aos="fade-up" data-aos-duration="1000">
									<div class="form-check me-auto">
										<input class="form-check-input" type="checkbox" value=""
											   id="flexCheckDefault">
										<label class="form-check-label">
											Entire unit wheelchair accessible
										</label>
									</div>
									<span class="ms-auto">98</span>
								</div>

								<div class="d-flex mt-3" data-aos="fade-up" data-aos-duration="1000">
									<div class="form-check me-auto" data-aos="fade-up" data-aos-duration="1000">
										<input class="form-check-input" type="checkbox" value=""
											   id="flexCheckDefault">
										<label class="form-check-label">
											Upper floor accessible by elevator
										</label>
									</div>
									<span class="ms-auto">98</span>
								</div>

								<div class="d-flex mt-3" data-aos="fade-up" data-aos-duration="1000">
									<div class="form-check me-auto" data-aos="fade-up" data-aos-duration="1000">
										<input class="form-check-input" type="checkbox" value=""
											   id="flexCheckDefault">
										<label class="form-check-label">
											Entire unit located on ground floor
										</label>
									</div>
									<span class="ms-auto">98</span>
								</div>
							</div>
						</form>
					</div>
				</div>

				<div class="col-lg-12 mt-4" data-aos="fade-up" data-aos-duration="1000">
					<div class="map position-relative">
						<img src="<?= base_url('assets/images/hotels-and-location/map-2.png') ?>" width="100%" height="auto"
							 class="img-fluid" alt="">
						<div class="position-absolute top-50 start-50 translate-middle">
							<a href="hotel-2.html" class="btn btn-primary btn-lg">Show on map</a>
						</div>
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
						<p class="small text-muted me-auto">1 - <?= $hotelCount ?> of <?= $hotelCount ?> Hotels</p>
					</div>

					<div class="col-lg-3">
						<form action="">
							<div class="mb-3">
								<select class="form-select" aria-label="Default select example">
									<option selected>Sort by: Our top picks</option>
									<option value="price_asc">Price: Low to High</option>
									<option value="price_desc">Price: High to Low</option>
									<option value="rating">Rating</option>
								</select>
							</div>
						</form>
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

					// Get currency from API response
					$currency = isset($apiResponse->Currency) ? $apiResponse->Currency : '&#8358;';
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

							// Calculate estimated taxes (5% of price)
							$hotelPrice = isset($hotel->Price) ? (float)$hotel->Price : 0;
							$taxAmount = $hotelPrice * 0.05;

							// Get rating label
							$rating = isset($hotel->Rating) ? (float)$hotel->Rating : 0;
							if ($rating >= 9) {
								$ratingLabel = 'Exceptional';
							} elseif ($rating >= 8) {
								$ratingLabel = 'Excellent';
							} elseif ($rating >= 7) {
								$ratingLabel = 'Very Good';
							} elseif ($rating >= 6) {
								$ratingLabel = 'Good';
							} else {
								$ratingLabel = 'Pleasant';
							}
							?>
							<div class="card border-0 mt-4" data-aos="fade-up" data-aos-duration="1000">
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
															<?php if (isset($hotel->Latitude) && isset($hotel->Longitude)): ?>
																<a href="https://www.google.com/maps/@<?= $hotel->Latitude ?>,<?= $hotel->Longitude ?>,15z" class="mx-1 text-decoration-underline" target="_blank">Show on map</a>
															<?php else: ?>
																<span class="mx-1">Show on map</span>
															<?php endif; ?>
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
																<?= $currency ?> <?= number_format($hotelPrice, 2) ?>
															</h5>
															<p class="small">+ <?= $currency ?> <?= number_format($taxAmount, 2) ?> taxes and charges</p>
															<form action="<?= site_url('hotel/index') ?>" method="post">
																<input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>"
																	   value="<?= $this->security->get_csrf_hash(); ?>" />
																<input type="hidden" name="hotelId" value="<?= isset($hotel->HotelCode) ? $hotel->HotelCode : '' ?>">
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
					<p class="text-center mt-4">No hotels found. Try adjusting your search criteria.</p>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>
