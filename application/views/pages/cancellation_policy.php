<?php
// Parse policy response
$policy = isset($policyResponse) ? $policyResponse : null;
?>

<!-- Cancellation Policy -->
<section class="py-5">
	<div class="container">
		<div class="row">
			<div class="col-lg-8 m-auto">
				<div class="card" data-aos="fade-up" data-aos-duration="1000">
					<div class="card-header bg-warning text-center">
						<h5 class="fw-bold my-auto">Cancellation Policy</h5>
					</div>
					<div class="card-body">
						<?php if ($this->session->flashdata('error')): ?>
						<div class="alert alert-danger">
							<?= $this->session->flashdata('error') ?>
						</div>
						<?php endif; ?>

						<?php if (isset($error)): ?>
						<div class="alert alert-danger">
							<i class="ri-error-warning-line"></i> <?= htmlspecialchars($error) ?>
						</div>
						<div class="text-center mt-4">
							<a href="<?= site_url('cancellation') ?>" class="btn btn-secondary">
								<i class="ri-arrow-left-line"></i> Try Again
							</a>
						</div>
						<?php else: ?>

						<!-- Booking Details -->
						<div class="alert alert-info">
							<div class="row">
								<div class="col-md-6">
									<strong>Booking ID:</strong> <?= htmlspecialchars($bookingId) ?>
								</div>
								<div class="col-md-6">
									<strong>Booking Reference:</strong> <?= htmlspecialchars($bookingCode) ?>
								</div>
							</div>
						</div>

						<!-- Policy Details from API -->
						<?php if ($policy): ?>
						<div class="card bg-light mb-4">
							<div class="card-body">
								<h6 class="fw-bold mb-3"><i class="ri-file-list-3-line"></i> Cancellation Terms</h6>

								<?php if (isset($policy->HotelName)): ?>
								<p><strong>Hotel:</strong> <?= htmlspecialchars((string) $policy->HotelName) ?></p>
								<?php endif; ?>

								<?php if (isset($policy->CheckIn)): ?>
								<p><strong>Check-in:</strong> <?= htmlspecialchars((string) $policy->CheckIn) ?></p>
								<?php endif; ?>

								<?php if (isset($policy->CheckOut)): ?>
								<p><strong>Check-out:</strong> <?= htmlspecialchars((string) $policy->CheckOut) ?></p>
								<?php endif; ?>

								<?php if (isset($policy->CancellationPolicy)): ?>
								<div class="mt-3 p-3 bg-white rounded border">
									<h6 class="fw-semibold text-danger">Policy Details:</h6>
									<p class="mb-0"><?= nl2br(htmlspecialchars((string) $policy->CancellationPolicy)) ?></p>
								</div>
								<?php endif; ?>

								<?php if (isset($policy->CancellationCharge)): ?>
								<div class="alert alert-warning mt-3 mb-0">
									<strong>Cancellation Charge:</strong>
									<?php
									$charge = (float) $policy->CancellationCharge;
									$currency = isset($policy->Currency) ? (string) $policy->Currency : 'USD';
									if ($currency === 'USD') {
										$chargeNaira = $charge * USD_TO_NGN_RATE;
										echo DISPLAY_CURRENCY_SYMBOL . number_format($chargeNaira, 2);
										echo ' (' . $currency . ' ' . number_format($charge, 2) . ')';
									} else {
										echo $currency . ' ' . number_format($charge, 2);
									}
									?>
								</div>
								<?php endif; ?>

								<?php if (isset($policy->RefundAmount)): ?>
								<div class="alert alert-success mt-3 mb-0">
									<strong>Refund Amount:</strong>
									<?php
									$refund = (float) $policy->RefundAmount;
									$currency = isset($policy->Currency) ? (string) $policy->Currency : 'USD';
									if ($currency === 'USD') {
										$refundNaira = $refund * USD_TO_NGN_RATE;
										echo DISPLAY_CURRENCY_SYMBOL . number_format($refundNaira, 2);
										echo ' (' . $currency . ' ' . number_format($refund, 2) . ')';
									} else {
										echo $currency . ' ' . number_format($refund, 2);
									}
									?>
								</div>
								<?php endif; ?>

								<?php if (isset($policy->Status)): ?>
								<p class="mt-3 mb-0"><strong>Current Status:</strong>
									<span class="badge bg-<?= strtolower((string) $policy->Status) === 'confirmed' ? 'success' : 'secondary' ?>">
										<?= htmlspecialchars((string) $policy->Status) ?>
									</span>
								</p>
								<?php endif; ?>

								<?php if (isset($policy->Message)): ?>
								<div class="alert alert-info mt-3 mb-0">
									<?= htmlspecialchars((string) $policy->Message) ?>
								</div>
								<?php endif; ?>
							</div>
						</div>
						<?php endif; ?>

						<!-- Cancellation Confirmation -->
						<div class="alert alert-danger">
							<h6 class="fw-bold"><i class="ri-error-warning-fill"></i> Warning</h6>
							<p class="mb-0">This action cannot be undone. Once cancelled, your booking will be permanently removed and any applicable cancellation charges will apply.</p>
						</div>

						<div class="row">
							<div class="col-md-6 mb-2">
								<a href="<?= site_url('cancellation') ?>" class="btn btn-outline-secondary w-100">
									<i class="ri-arrow-left-line"></i> Go Back
								</a>
							</div>
							<div class="col-md-6 mb-2">
								<?= form_open('cancellation/confirm') ?>
									<input type="hidden" name="confirm_cancel" value="1">
									<button type="submit" class="btn btn-danger w-100"
										onclick="return confirm('Are you sure you want to cancel this booking? This action cannot be undone.');">
										<i class="ri-close-circle-line"></i> Confirm Cancellation
									</button>
								<?= form_close() ?>
							</div>
						</div>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
<!-- Cancellation Policy -->
