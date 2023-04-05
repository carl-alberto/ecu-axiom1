<div id="reverse-form-wrap">
	<div class="container">
		<div class="card card-body">
			<div class="row">
				<div class="col-sm-8">
					<form id="reverse-lookup-form">
						<div class="control-group">
							<label class="control-label required" for="phoneNumber">Phone Number</label>
							<div class="controls">
								<input name="phoneNumber" id="phoneNumber" type="text">
							</div>
						</div>
						<button type="submit" role="button" label="Search" class="ecu-event-tracking btn btn-primary" data-ga-category="Page:Directory" data-ga-action="Department Search" data-ga-label="Submit" id="reverseSearch">Search</button>
					</form>
				</div>
				<div class="col-sm-4">
					<img class="loading-gif" alt="" src=<?php echo plugins_url( '../../assets/loading.gif', __FILE__ ); ?> />
				</div>
			</div>
		</div>
	</div>
</div>
