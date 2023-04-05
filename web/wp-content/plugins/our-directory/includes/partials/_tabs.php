<div class="container">
	<nav class="nav" aria-label="directory search">
		<ul class="nav nav-tabs" id="navTabs" role="tablist">
			<li class="nav-item">
			    <a class="nav-link active" id="people-tab" data-toggle="tab" href="#people" role="tab" aria-controls="people" aria-selected="true">People</a>
			</li>
			<li class="nav-item">
			    <a class="nav-link" id="department-tab" data-toggle="tab" href="#department" role="tab" aria-controls="department" aria-selected="false">Departments</a>
			</li>
			<li class="nav-item">
			    <a class="nav-link" id="reverse-tab" data-toggle="tab" href="#reverse" role="tab" aria-controls="reverse" aria-selected="false">Reverse Lookup</a>
			</li>
		</ul>
	</nav>
</div>
<div class="tab-content" id="navTabsContent">
	<div class="tab-pane fade show active" id="people" role="tabpanel" aria-labelledby="people-tab">
		    <?php include('_people-form.php'); ?>
		    <?php include('_people-results.php'); ?>
	</div>
	<div class="tab-pane fade" id="department" role="tabpanel" aria-labelledby="department-tab">
			<?php include('_department-form.php'); ?>
			<?php include('_department-menu.php'); ?>
			<?php include('_department-results.php'); ?>
	</div>
	<div class="tab-pane fade" id="reverse" role="tabpanel" aria-labelledby="reverse-tab">
			<?php include('_reverse-form.php'); ?>
			<?php include('_reverse-results.php'); ?>
	</div>
</div>
