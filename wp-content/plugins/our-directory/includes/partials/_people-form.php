<div id="people-form-wrap">
	<div class="container">
		<div class="card card-body">
			<form id="people-form">
				<div class="row">
					<div class="col-sm-4">
						<div class="control-group">
							<label class="control-label" for="firstName">First Name</label>
							<div class="controls">
								<input name="firstName" id="firstName" type="text">
							</div>
						</div>
						<div class="control-group">
							<label class="control-label" for="lastName">Last Name</label>
							<div class="controls">
								<input name="firstName" id="lastName" type="text">
							</div>
						</div>
					</div>
					<div class="col-sm-4">
						<div class="control-group" role="radiogroup" aria-labelledby="radio-label" >
							<label id="radio-label" class="control-label required ecu" for="filter">Search For:</label>
							<div class="controls">
								<input id="filter" type="hidden" value="" name="filter">
								<label class="radio">
									<input id="filter_2" value="both" checked="checked" type="radio" name="filter"> All
								</label>
								<label class="radio">
									<input id="filter_0" value="faculty" type="radio" name="filter"> Faculty/Staff
								</label>
								<label class="radio">
									<input id="filter_1" value="students" type="radio" name="filter"> Students
								</label>
							</div>
						</div>
						<button type="submit" role="button" label="Search" class="ecu-event-tracking btn btn-primary" data-ga-category="Page:Directory" data-ga-action="People Search" data-ga-label="Submit" id="searchPeople" >Search</button>
					</div>
					<div class="col-sm-4">
						<img class="loading-gif" alt="" src=<?php echo plugins_url( '../../assets/loading.gif', __FILE__ ); ?> />
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
