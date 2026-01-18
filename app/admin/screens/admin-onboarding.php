<div class="wrap">
    <h1><?php _e( 'Onboarding dinamico', 'comorg' ); ?></h1>

    <form method="post">

        <?php wp_nonce_field( 'comorg_onboarding_action', 'comorg_onboarding_nonce' ); ?>

        <h2><?php _e( 'Campi chiave', 'comorg' ); ?></h2>

        <table class="form-table">
            <tr>
                <th><?php _e( 'Campo PF/PG', 'comorg' ); ?></th>
                <td>
                    <select name="field_pfpg">
                        <option value="">—</option>
                        <?php foreach ( $fields as $f ) : ?>
                            <option value="<?php echo esc_attr( $f ); ?>" <?php selected( $settings['field_pfpg'], $f ); ?>>
                                <?php echo esc_html( $f ); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>

            <tr>
                <th><?php _e( 'Campo tipo organizzazione', 'comorg' ); ?></th>
                <td>
                    <select name="field_type">
                        <option value="">—</option>
                        <?php foreach ( $fields as $f ) : ?>
                            <option value="<?php echo esc_attr( $f ); ?>" <?php selected( $settings['field_type'], $f ); ?>>
                                <?php echo esc_html( $f ); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>

            <tr>
                <th><?php _e( 'Campo nome organizzazione', 'comorg' ); ?></th>
                <td>
                    <select name="field_name">
                        <option value="">—</option>
                        <?php foreach ( $fields as $f ) : ?>
                            <option value="<?php echo esc_attr( $f ); ?>" <?php selected( $settings['field_name'], $f ); ?>>
                                <?php echo esc_html( $f ); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
        </table>

        <h2><?php _e( 'Mappatura valori → Profile Types', 'comorg' ); ?></h2>

        <table class="widefat">
            <thead>
                <tr>
                    <th><?php _e( 'Valore campo', 'comorg' ); ?></th>
                    <th><?php _e( 'Profile Type', 'comorg' ); ?></th>
                </tr>
            </thead>
            <tbody id="comorg-map-rows">

                <?php if ( ! empty( $settings['map_types'] ) ) : ?>
                    <?php foreach ( $settings['map_types'] as $key => $value ) : ?>
                        <tr>
                            <td><input type="text" name="map_key[]" value="<?php echo esc_attr( $key ); ?>"></td>
                            <td><input type="text" name="map_value[]" value="<?php echo esc_attr( $value ); ?>"></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>

                <tr>
                    <td><input type="text" name="map_key[]" value=""></td>
                    <td><input type="text" name="map_value[]" value=""></td>
                </tr>

            </tbody>
        </table>

        <p><button type="button" class="button" onclick="comorgAddMapRow()">+ Aggiungi riga</button></p>

        <h2><?php _e( 'Regole slug', 'comorg' ); ?></h2>

        <table class="widefat">
            <thead>
                <tr>
                    <th><?php _e( 'Profile Type', 'comorg' ); ?></th>
                    <th><?php _e( 'Metodo', 'comorg' ); ?></th>
                    <th><?php _e( 'Prefisso', 'comorg' ); ?></th>
                    <th><?php _e( 'Lunghezza', 'comorg' ); ?></th>
                </tr>
            </thead>
            <tbody id="comorg-slug-rows">

                <?php if ( ! empty( $settings['slug_rules'] ) ) : ?>
                    <?php foreach ( $settings['slug_rules'] as $ptype => $rule ) : ?>
                        <tr>
                            <td><input type="text" name="slug_profile_type[]" value="<?php echo esc_attr( $ptype ); ?>"></td>
                            <td>
                                <select name="slug_method[]">
                                    <option value="random" <?php selected( $rule['method'], 'random' ); ?>>Random</option>
                                    <option value="sanitize_name" <?php selected( $rule['method'], 'sanitize_name' ); ?>>Sanitize nome</option>
                                </select>
                            </td>
                            <td><input type="text" name="slug_prefix[]" value="<?php echo esc_attr( $rule['prefix'] ); ?>"></td>
                            <td><input type="number" name="slug_length[]" value="<?php echo esc_attr( $rule['length'] ); ?>"></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>

                <tr>
                    <td><input type="text" name="slug_profile_type[]" value=""></td>
                    <td>
                        <select name="slug_method[]">
                            <option value="random">Random</option>
                            <option value="sanitize_name">Sanitize nome</option>
                        </select>
                    </td>
                    <td><input type="text" name="slug_prefix[]" value=""></td>
                    <td><input type="number" name="slug_length[]" value="10"></td>
                </tr>

            </tbody>
        </table>

        <p><button type="button" class="button" onclick="comorgAddSlugRow()">+ Aggiungi riga</button></p>

        <p><button type="submit" class="button button-primary"><?php _e( 'Salva impostazioni', 'comorg' ); ?></button></p>

    </form>
</div>

<script>
function comorgAddMapRow() {
    const tbody = document.getElementById('comorg-map-rows');
    const row = document.createElement('tr');
    row.innerHTML = '<td><input type="text" name="map_key[]"></td><td><input type="text" name="map_value[]"></td>';
    tbody.appendChild(row);
}

function comorgAddSlugRow() {
    const tbody = document.getElementById('comorg-slug-rows');
    const row = document.createElement('tr');
    row.innerHTML =
        '<td><input type="text" name="slug_profile_type[]"></td>' +
        '<td><select name="slug_method[]"><option value="random">Random</option><option value="sanitize_name">Sanitize nome</option></select></td>' +
        '<td><input type="text" name="slug_prefix[]"></td>' +
        '<td><input type="number" name="slug_length[]" value="10"></td>';
    tbody.appendChild(row);
}
</script>
