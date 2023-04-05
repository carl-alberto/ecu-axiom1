<div class="container">
	<nav class="nav" aria-label="directory search">
		<ul class="nav nav-tabs" id="navTabs" role="tablist">
			<li class="nav-item">
			    <h3><a class="nav-link active" id="people-tab" data-toggle="tab" href="#people" role="tab" aria-controls="people" aria-selected="true">Cotanche Employee Search</a></h3>
			</li>			
		</ul>
	</nav>
</div>
<div class="tab-content" id="navTabsContent">
	<div class="tab-pane fade show active" id="people" role="tabpanel" aria-labelledby="people-tab">
		    <?php include('_people-form.php'); ?>
		    <?php include('_people-results.php'); ?>
	</div>	
</div>
