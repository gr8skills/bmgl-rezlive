<!-- preloader -->
<div class="preloader js-preloader">
</div>
<!-- preloader -->


<!-- Navbar -->
<div class="navbar p-0">
	<nav class="navbar navbar-expand-lg">
		<div class="container home-navbar-links">
			<a class="navbar-brand" href="index.html">
				<img src="<?= base_url('assets/images/logo.png') ?>" alt="Logo" height="100%">
			</a>
			<button class="navbar-toggler border-0 bg-transparent" type="button" data-bs-toggle="modal"
					data-bs-target="#logInModal">
				<span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse" id="navbarSupportedContent">
				<ul class="navbar-nav ms-auto mb-2 mb-lg-0">
					<li class="nav-item">
						<div class="btn-group" role="group" aria-label="Basic example">
							<button type="button" class="btn btn-primary fw-semibold" data-bs-toggle="modal"
									data-bs-target="#logInModal">Login</button>
							<button type="button" class="btn btn-primary">|</button>
							<button type="button" class="btn btn-primary fw-semibold" data-bs-toggle="modal"
									data-bs-target="#signUpModal">Sign Up</button>
						</div>
					</li>
				</ul>
			</div>
		</div>
	</nav>
</div>
<!-- Navbar -->

<!-- Login/signup modal -->


<!-- Login Modal -->
<div class="modal fade" id="logInModal" data-bs-keyboard="true" tabindex="-1" aria-labelledby="logInModal"
	 aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-md">

		<div class="modal-content">
			<div class="modal-header border-0">
				<button type="button" class="btn-close color-primary" data-bs-dismiss="modal"
						aria-label="Close"></button>
			</div>
			<div class="ms-4 me-4">
				<ul class="nav nav-pills justify-content-center nav-fill account-modal grey-bg mx-auto" id="myTab"
					role="tablist">
					<li class="nav-item mb-2 d-grid px-2" role="presentation">
						<button class="nav-link active" id="home-tab" data-bs-toggle="tab"
								data-bs-target="#home-tab-pane" type="button" role="tab" aria-controls="home-tab-pane"
								aria-selected="true">Personal</button>
					</li>
					<li class="nav-item mb-2 d-grid px-2" role="presentation">
						<button class="nav-link" id="profile-tab" data-bs-toggle="tab"
								data-bs-target="#profile-tab-pane" type="button" role="tab"
								aria-controls="profile-tab-pane" aria-selected="false">Agent</button>
					</li>

				</ul>
			</div>
			<div class="tab-content" id="myTabContent">
				<!-- Personal -->
				<div class="tab-pane fade show active" id="home-tab-pane" role="tabpanel" aria-labelledby="home-tab"
					 tabindex="0">
					<div class="container">
						<div class="row">
							<ul class="list-unstyled">
								<li>
									<a href="" class="text-primary text-decoration-none"><i
											class="ri-google-fill ri-lg mx-2"></i> Sign in
										with Google
										account</a>
								</li>
								<li>
									<a href="" class="text-primary text-decoration-none"><i
											class="ri-facebook-fill ri-lg mx-2"></i> Sign
										in with Facebook</a>
								</li>
								<li>
									<a href="" class="text-primary text-decoration-none"><i
											class="ri-apple-fill ri-lg mx-2"></i> Sign in
										with Apple ID</a>
								</li>
							</ul>
						</div>
						<div class="row p-3 mt-3">
							<h5>Or Sign In with</h5>
							<form>
								<div class="mb-3">
									<input type="email" class="form-control" placeholder="Phone Number or Email"
										   id="exampleInputEmail1" aria-describedby="emailHelp">
								</div>
								<div class="mb-3">
									<input type="password" class="form-control" placeholder="Password"
										   id="exampleInputPassword1">
								</div>
								<div class="mb-3 d-flex form-check">
									<div class="me-auto">
										<input type="checkbox" class="form-check-input" id="exampleCheck1">
										<label class="form-check-label" for="exampleCheck1">Remember me</label>
									</div>

									<div class="ms-auto">
										<a href="" class="text-primary text-decoration-none">Forgot Password</a>
									</div>
								</div>
								<div class="d-grid">
									<button type="submit" class="btn btn-primary btn-lg">Sign In</button>
								</div>
								<div class="col-lg-12 d-flex mt-4">
									<div class="me-auto">
										<p class="small">Don't have an account?</p>
									</div>
									<div class="ms-auto">
										<a href="" class="text-primary text-decoration-none" data-bs-toggle="modal"
										   data-bs-target="#signUpModal">Sign Up</a>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
				<!-- Personal -->



				<!-- Agent -->
				<div class="tab-pane fade" id="profile-tab-pane" role="tabpanel" aria-labelledby="profile-tab"
					 tabindex="0">
					<div class="tab-pane fade show active" id="home-tab-pane" role="tabpanel"
						 aria-labelledby="home-tab" tabindex="0">
						<div class="container">
							<div class="row p-3 mt-3">
								<form>
									<div class="mb-3">
										<input type="email" class="form-control" placeholder="Phone Number or Email"
											   id="exampleInputEmail1" aria-describedby="emailHelp">
									</div>
									<div class="mb-3">
										<input type="password" class="form-control" placeholder="Password"
											   id="exampleInputPassword1">
									</div>
									<div class="mb-3 d-flex form-check">
										<div class="me-auto">
											<input type="checkbox" class="form-check-input" id="exampleCheck1">
											<label class="form-check-label" for="exampleCheck1">Remember me</label>
										</div>

										<div class="ms-auto">
											<a href="#" class="text-primary text-decoration-none">Forgot
												Password</a>
										</div>
									</div>
									<div class="d-grid">
										<button type="submit" class="btn btn-primary btn-lg">Sign In</button>
									</div>
									<div class="col-lg-6 m-auto mt-4">
										<a href="" class="text-primary text-decoration-underline">Apply for Agent
											Account</a>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- Login Modal -->

<!-- Sign-up Modal -->
<div class="modal fade" id="signUpModal" data-bs-keyboard="true" tabindex="-1" aria-labelledby="signUpModal"
	 aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-md">
		<div class="modal-content">
			<div class="modal-header border-0">
				<h3 class="fw-bold">Sign Up</h3>
				<button type="button" class="btn-close color-primary" data-bs-dismiss="modal"
						aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div class="container">
					<div class="row">
						<form>
							<div class="mb-3">
								<input type="email" class="form-control" placeholder="Name" id="exampleInputEmail1"
									   aria-describedby="emailHelp">
							</div>
							<div class="mb-3">
								<input type="email" class="form-control" placeholder="Email" id="exampleInputEmail1"
									   aria-describedby="emailHelp">
							</div>
							<div class="mb-3">
								<input type="email" class="form-control" placeholder="Phone Number"
									   id="exampleInputEmail1" aria-describedby="emailHelp">
							</div>
							<div class="mb-3">
								<input type="password" class="form-control" placeholder="Password"
									   id="exampleInputPassword1">
							</div>

							<div class="d-grid">
								<button type="submit" class="btn btn-primary btn-lg">Create Account</button>
							</div>
							<div class="col-lg-12 m-auto mt-4">
								<p class="text-muted text-center">By selecting Create Account, you have agreed to
									our Terms and Privacy Policy</p>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- Sign-up Modal -->
