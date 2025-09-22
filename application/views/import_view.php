<!DOCTYPE html>
<html>
<head>
	<title>Import Excel</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-5">

<h2 class="mb-4">Import Cities from Excel</h2>

<?php if ($this->session->flashdata('message')): ?>
	<div class="alert alert-info">
		<?= $this->session->flashdata('message'); ?>
	</div>
<?php endif; ?>

<form action="<?= site_url('import/upload'); ?>" method="post" enctype="multipart/form-data" class="border p-4 rounded shadow-sm bg-light">
	<input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>"
		   value="<?= $this->security->get_csrf_hash(); ?>" />

	<div class="mb-3">
		<label for="file" class="form-label fw-bold">Choose Excel File (.xlsx / .xls)</label>
		<input type="file" name="file" id="file" class="form-control" required>
	</div>

	<button type="submit" class="btn btn-primary">Upload & Import</button>
</form>


</body>
</html>
