<?php global $post; $meta = get_post_meta( $post->ID ); ?>
<style type="text/css">
#physician_meta label {
    color: #23282d;
    line-height: 1.3;
    font-weight: 600;
}
#physician_meta input[type=text] {
    display: block;
    width: 100%;
    margin-bottom: 15px;
}
</style>
<div id="physician_meta">
    <label for="physician_name">Full Name</label><br />
    <input name="physician_name" type="text" id="physician_name" placeholder="John A. Smith" value="<?php echo isset($meta['physician_name']) ? $meta['physician_name'][0] : ''; ?>">
    <label for="physician_accred">Accreditation</label><br />
    <input name="physician_accred" type="text" id="physician_accred" placeholder="MD, MS, etc." value="<?php echo isset($meta['physician_accred']) ? $meta['physician_accred'][0] : ''; ?>">
    <label for="physician_ecu_title">ECU Title</label><br />
    <input name="physician_ecu_title" type="text" id="physician_ecu_title" value="<?php echo isset($meta['physician_ecu_title']) ? $meta['physician_ecu_title'][0] : ''; ?>">
    <label for="physician_clinical_title">Clinical Title</label><br />
    <input name="physician_clinical_title" type="text" id="physician_clinical_title" value="<?php echo isset($meta['physician_clinical_title']) ? $meta['physician_clinical_title'][0] : ''; ?>">
    <label for="physician_location">Location</label><br />
    <input name="physician_location" type="text" id="physician_location" value="<?php echo isset($meta['physician_location']) ? $meta['physician_location'][0] : 'Greenville, NC'; ?>">
    <label for="physician_profile">Academic Profile <small>(URL of doctor on academic site)</small></label><br />
    <input name="physician_profile" type="text" id="physician_profile" value="<?php echo isset($meta['physician_profile']) ? $meta['physician_profile'][0] : ''; ?>">
</div>