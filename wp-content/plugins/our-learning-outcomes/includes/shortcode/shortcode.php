<?php
namespace OUR\LEARNINGOUTCOMES;

/*
 * Registers learning outcomes shortcode
 *
 * @att string post_type string
 * post_type: string: program, co
 */
add_shortcode( 'learning_outcomes', __NAMESPACE__ . '\learning_outcomes' );
function learning_outcomes( $atts) {
	wp_enqueue_script( 'learning_outcomes_script' );
    wp_enqueue_style( 'learning_outcomes_style');
	if (isset($atts['post_type'])) {
		$post_type = $atts['post_type'];
	}    else {
		$post_type = NULL;
	}
	if (isset($atts['selected'])) {
		$selected = $atts['selected'];
	}    else    {
		$selected = NULL;
	}
	if (isset($atts['college_id'])) {
		$college_id = $atts['college_id'];
		$selected_college = '/college/' . get_post_field( 'post_name', $atts['college_id']);
	}    else    {
		$college_id = NULL;
	}

    ob_start(); ?>
    <div id="lo-wrapper">
		<div id="learning-outcome-menu">
			<div class="row">
				<!----------------------------- Learning Outcome --------------------------------------- -->
				<div class="col-md-4">
					<label for="learning_outcome">Learning Outcomes</label>
					<?php
						if ($post_type == 'college' || $post_type == 'program') {
							$general = NULL;
							$lo = 'selected';
						}    elseif($post_type == 'competency')    {
							$general = 'selected';
							$lo = NULL;
						}    else    {
							$general = NULL;
							$lo = NULL;
						}
					?>
					<select name="learning_outcome" id="learning_outcome">
						<option value="0">Select a Learning Outcome</option>
						<option value="1" <?php echo $general; ?> >General Education Competencies</option>
						<option value="2" <?php echo $lo; ?>>Program Learning Outcomes</option>
					</select>
				</div>
				<!----------------------------- Competency --------------------------------------- -->
				<div class="col-md-4">
					<div id="competency-wrap" class="d-none">
						<label for="competency_selector">Competency</label>
						<select name="competency_selector" id="competency_selector">
							<option value="0">Select a Competency</option>
							<?php
								$comps = get_posts(array('nopaging'=>true, 'post_type'=>'competency', 'orderby'=>'title', 'order'=>'ASC', 'posts_per_page'=>1000, 'numberposts'=>1000));
								if ($comps) {
									foreach ($comps as $c) {
										$link = $c->post_name;
										$select[$link] = $c->post_title;
									}
									foreach ($select as $link => $title) {
										$link = '/competency/' . $link;
											if ($post_type == 'competency') {
												if($selected == $link) {
													$comp_s = 'selected';
												}   else   {
													$comp_s = NULL;
												}
											}
										echo '<option value="'.$link.'" ' . $comp_s . '>'.$title.'</option>';
									}
								}

							?>
						</select>
					</div>
					<!----------------------------- College --------------------------------------- -->
					<div id="college-wrap" class="d-none" >
						<label for="college_selector">College or School</label>
						<select name="college_selector" id="college_selector">
							<option value="0">Select a College or School</option>
							<?php
								$colls = get_posts(array('nopaging'=>true, 'post_type'=>'college', 'numberposts'=>1000,'orderby'=>'title', 'order'=>'ASC', 'posts_per_page'=>1000));
								if ($colls) {
									foreach ($colls as $c) {
										$link = $c->post_name;
										$select1[$link] = $c->post_title;
									}
									foreach ($select1 as $link => $title) {
										$link = '/college/' . $link;
										if($selected_college == $link) {
											$coll_s = 'selected';
										}    else    {
											$coll_s = NULL;
										}
										echo '<option value="'.$link.'" ' . $coll_s . '>'.$title.'</option>';
									}
								}
							?>
						</select>
					</div>
				</div>
				<!----------------------------- Program --------------------------------------- -->
				<div class="col-md-4">
					<div id="program-wrap" class="d-none" >
					 	<label for="program_selector">Program</label>
						<select name="program_selector" id="program_selector">
							<option value="0">Select a Program</option>
							<?php
								$args = array(
									'post_type'              => 'program',
									'order'                  => 'ASC',
									'orderby'                => 'title',
									'posts_per_page '		 => 1000,
									'numberposts'			 => 1000,
									'nopaging'	 			 => true,
									'meta_key'   			 => 'college_selector',
									'meta_query'             => array(
										//'relation' => 'AND',
										array(
											'key'     => 'college_selector',
											'value'   => $college_id,
										),

									),
								);

								// The Query
								$progs = new \WP_Query( $args );

								if ($progs) {
									foreach ($progs->posts as $p) {
										$link = $p->post_name;
										$select2[$link] = $p->post_title;
									}
									foreach ($select2 as $link => $title) {
										$link = '/program/' . $link;
										if ($post_type == 'program') {
											if($selected == $link) {
												$prog_s = 'selected';
											}   else   {
												$prog_s = NULL;
											}
										}
										echo '<option value="'.$link.'" ' . $prog_s . '>'.$title.'</option>';
									}
								}
							?>
						</select>
					</div>
				</div>
			</div>
		</div>
	<hr />
    <?php $output = ob_get_contents();
    ob_end_clean();
    return $output;
}
