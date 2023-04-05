<?php global $post; $meta = get_post_meta( $post->ID ); ?>
<style type="text/css">
#counselor_meta label {
    color: #23282d;
    line-height: 1.3;
    font-weight: 600;
}
#counselor_meta input[type=text] {
    display: block;
    width: 100%;
    margin-bottom: 15px;
}
</style>
<div id="counselor_meta">
    <label for="counselor_title">Job Title</label><br />
    <input name="counselor_title" type="text" id="counselor_title" value="<?php echo isset($meta['counselor_title']) ? $meta['counselor_title'][0] : ''; ?>">
    <label for="counselor_email">Email</label><br />
    <input name="counselor_email" type="text" id="counselor_email" value="<?php echo isset($meta['counselor_email']) ? $meta['counselor_email'][0] : ''; ?>">
    <label for="counselor_phone">Phone</label><br />
    <input name="counselor_phone" type="text" id="counselor_phone" value="<?php echo isset($meta['counselor_phone']) ? $meta['counselor_phone'][0] : ''; ?>">
    <label for="counselor_appointment">Schedule Appointment Link</label><br />
    <input name="counselor_appointment" type="text" id="counselor_appointment" value="<?php echo isset($meta['counselor_appointment']) ? $meta['counselor_appointment'][0] : ''; ?>">
    <label for="counselor_type">Type</label><br />
    <select id="counselor_type" name="counselor_type" type="text">
        <option value="freshman" <?php echo $meta['counselor_type'][0] === 'freshman' ? 'selected' : ''; ?>>Freshman</option>
        <option value="transfer" <?php echo $meta['counselor_type'][0] === 'transfer' ? 'selected' : ''; ?>>Transfer</option>
    </select><br /><br />
    <label for="default_counselor"><input type="checkbox" name="default_counselor" <?php echo isset($meta['default_counselor']) ? 'checked' : ''; ?>> Default counselor</label><br />
    <small>Counselor displays if no results are found for the selected student type.</small>
</div>