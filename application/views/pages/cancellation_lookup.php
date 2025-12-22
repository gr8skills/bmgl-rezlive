<!-- Cancel Booking Lookup -->
<section class="py-5">
	<div class="container">
		<div class="row">
			<div class="col-lg-6 m-auto">
				<div class="card" data-aos="fade-up" data-aos-duration="1000">
					<div class="card-header bg-warning text-center">
						<h5 class="fw-bold my-auto">Cancel Hotel Booking</h5>
					</div>
					<div class="card-body">
						<?php if ($this->session->flashdata('error')): ?>
						<div class="alert alert-danger">
							<?= $this->session->flashdata('error') ?>
						</div>
						<?php endif; ?>

						<p class="text-muted mb-4">Enter your booking details to view the cancellation policy and cancel your reservation.</p>

						<?= form_open('cancellation/policy', array('class' => 'needs-validation')) ?>
							<div class="mb-3">
								<label for="bookingId" class="form-label fw-semibold">Booking ID <span class="text-danger">*</span></label>
								<input type="text" class="form-control" id="bookingId" name="bookingId"
									placeholder="e.g., 89868B" required>
								<div class="form-text">The Booking ID provided in your confirmation email.</div>
							</div>

							<div class="mb-4">
								<label for="bookingCode" class="form-label fw-semibold">Booking Reference <span class="text-danger">*</span></label>
								<input type="text" class="form-control" id="bookingCode" name="bookingCode"
									placeholder="e.g., REZ68B80F21" required>
								<div class="form-text">The Booking Reference/Code from your confirmation.</div>
							</div>

							<div class="d-grid">
								<button type="submit" class="btn btn-warning btn-lg fw-bold">
									<i class="ri-search-line"></i> Look Up Booking
								</button>
							</div>
						<?= form_close() ?>
					</div>
				</div>

				<div class="text-center mt-4" data-aos="fade-up" data-aos-duration="1000">
					<a href="<?= site_url('home') ?>" class="btn btn-outline-secondary">
						<i class="ri-arrow-left-line"></i> Back to Home
					</a>
				</div>
			</div>
		</div>
	</div>
</section>
<!-- Cancel Booking Lookup -->
