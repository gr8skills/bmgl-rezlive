<?php
// Get cancellation details from session
$cancellation = isset($cancellation) ? $cancellation : array();
?>

<!-- Cancellation Success -->
<section class="py-5">
	<div class="container">
		<div class="row" data-aos="fade-up" data-aos-duration="1000">
			<div class="col-lg-4 m-auto mb-5 text-center">
				<div class="mb-4">
					<i class="ri-checkbox-circle-fill text-success" style="font-size: 80px;"></i>
				</div>
				<h3 class="text-success fw-bold">Booking Cancelled</h3>
				<p class="text-muted">Your hotel reservation has been successfully cancelled.</p>
			</div>
		</div>

		<div class="row">
			<div class="col-lg-6 m-auto">
				<div class="card" data-aos="fade-up" data-aos-duration="1000">
					<div class="card-header bg-secondary text-white text-center">
						<h5 class="fw-bold my-auto">Cancellation Confirmation</h5>
					</div>
					<div class="card-body">
						<?php if ($this->session->flashdata('success')): ?>
						<div class="alert alert-success">
							<i class="ri-checkbox-circle-line"></i> <?= $this->session->flashdata('success') ?>
						</div>
						<?php endif; ?>

						<div class="row g-3 mt-2">
							<div class="col-md-6">
								<p class="small mb-1 text-muted">Booking ID:</p>
								<p class="fw-bold"><?= htmlspecialchars($cancellation['bookingId'] ?? 'N/A') ?></p>
							</div>
							<div class="col-md-6">
								<p class="small mb-1 text-muted">Booking Reference:</p>
								<p class="fw-bold"><?= htmlspecialchars($cancellation['bookingCode'] ?? 'N/A') ?></p>
							</div>
						</div>

						<div class="row g-3">
							<div class="col-md-6">
								<p class="small mb-1 text-muted">Status:</p>
								<p class="fw-bold">
									<span class="badge bg-danger"><?= htmlspecialchars($cancellation['status'] ?? 'Cancelled') ?></span>
								</p>
							</div>
							<?php if (!empty($cancellation['cancellationId'])): ?>
							<div class="col-md-6">
								<p class="small mb-1 text-muted">Cancellation ID:</p>
								<p class="fw-bold"><?= htmlspecialchars($cancellation['cancellationId']) ?></p>
							</div>
							<?php endif; ?>
						</div>

						<?php if (!empty($cancellation['message'])): ?>
						<div class="alert alert-info mt-3">
							<?= htmlspecialchars($cancellation['message']) ?>
						</div>
						<?php endif; ?>

						<hr>

						<p class="text-muted small">
							<i class="ri-information-line"></i>
							If you have any questions about your cancellation or refund, please contact our support team.
						</p>

						<div class="text-center mt-4">
							<a href="<?= site_url('home') ?>" class="btn btn-primary btn-lg px-5">
								<i class="ri-home-line"></i> Back to Home
							</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
<!-- Cancellation Success -->
