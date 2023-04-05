<table class='table'>
    <tr>
        <th>Icon</th>
        <th>Description</th>
    </tr>

    <?php
        foreach ( $available_technology as $equipment ) {

            echo  '<tr>';
                echo '<td>';
                    echo '<img alt="'. esc_attr($equipment->equipment_tooltip) .'" src="' . CDN_IMAGE_URL . 'classroomtech-equipment/' . esc_attr($equipment->icon) .'" />';
                echo '</td>';
                echo '<td>';
                    echo esc_html($equipment->name) . '<br/>' . wp_kses_post($equipment->description, 'post');
                echo '</td>';
            echo '</tr>';
        }
    ?>
</table>
