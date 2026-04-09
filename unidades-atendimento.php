<?php

/**
 * Plugin Name: Unidades de Atendimento
 * Description: CRUD de Unidades de Atendimento com shortcode para exibição em cards filtráveis.
 * Version: 1.3.1
 * Author: Marcos Cordeiro
 * Author URI:        https://github.com/marcoscti
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

if (!defined('ABSPATH')) exit;

define('UA_PLUGIN_URL', plugin_dir_url(__FILE__));

add_action('init', function () {
    register_post_type('unidade_atendimento', [
        'labels' => [
            'name' => 'Unidades de Atendimento',
            'singular_name' => 'Unidade de Atendimento',
        ],
        'public' => false,
        'show_ui' => true,
        'menu_icon' => 'dashicons-plus-alt',
        'supports' => ['title', 'editor', 'thumbnail'],
    ]);
});

add_action('add_meta_boxes', function () {
    add_meta_box('ua_dados', 'Dados da Unidade', 'ua_render_meta', 'unidade_atendimento');
});

function ua_render_meta($post)
{
    $fields = [
        'endereco' => 'Endereço',
        'telefone' => 'Telefone',
        'link' => 'Link',
        'tipo' => 'Tipo',
        'teleconsulta' => 'Teleconsulta',
        'telepediatria' => 'Telepediatria',
        'ativo' => 'Ativo'
    ];
    foreach ($fields as $key => $label) {
        $$key = get_post_meta($post->ID, $key, true);
    }
?>
    <p><label>Endereço<br><input type="text" name="endereco" value="<?php echo esc_attr($endereco); ?>" style="width:100%"></label></p>
    <label>Telefone<br><input type="text" name="telefone" value="<?php echo esc_attr($telefone); ?>" style="width:100%"></label></p>
    <p><label>Link<br><input type="url" name="link" value="<?php echo esc_attr($link); ?>" style="width:100%"></label></p>
    <p><label>Tipo<br>
            <select name="tipo">
                <option value="Hospital" <?php selected($tipo, 'Hospital'); ?>>Hospital</option>
                <option value="UPA" <?php selected($tipo, 'UPA'); ?>>UPA</option>
            </select>
        </label></p>
    <p><label><input type="checkbox" name="ativo" value="1" <?php checked($ativo, '1'); ?>> Ativo</label></p>
    <p><label><input type="checkbox" name="teleconsulta" value="1" <?php checked($teleconsulta, '1'); ?>> Teleconsulta</label></p>
    <p><label><input type="checkbox" name="telepediatria" value="1" <?php checked($telepediatria, '1'); ?>> Telepediatria</label></p>
<?php
}

add_action('save_post', function ($post_id) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    foreach (['endereco', 'telefone', 'link', 'tipo', 'teleconsulta', 'telepediatria', 'ativo'] as $field) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, $field, $_POST[$field]);
        } else {
            delete_post_meta($post_id, $field);
        }
    }
});

add_shortcode('unidades_atendimento', function () {
    wp_enqueue_style('ua-style', UA_PLUGIN_URL . 'assets/style.css', [], "1.3.1", "all");
    wp_enqueue_script('ua-script', UA_PLUGIN_URL . 'assets/script.js', [], "1.3.1", "all");

    $q = new WP_Query([
        'post_type' => 'unidade_atendimento',
        'posts_per_page' => -1,
        'meta_query' => [
            ['key' => 'ativo', 'value' => '1']
        ]
    ]);

    ob_start(); ?>
    <div class="ua-filtros-container">
        <div class="ua-filtros">
            <strong>Filtrar por:</strong>
            <div class="ua-filtros-button">
                <button data-filter="Todos">Todos</button>
                <button data-filter="Hospital">Hospital</button>
                <button data-filter="UPA">UPA</button>
            </div>
        </div>
        <div class="ua-count-itens">
            <input type="search" id="ua-search-input" placeholder="Busque por nome ou endereço...">
        </div>
    </div>
    <div class="ua-cards">
        <?php while ($q->have_posts()): $q->the_post();
            $tipo = get_post_meta(get_the_ID(), 'tipo', true); ?>
            <div class="ua-card" data-tipo="<?php echo esc_attr($tipo); ?>">
                <div class="ua-card-content">
                    <div>
                        <h3 class="ua-card-title"><?php the_title(); ?></h3>
                        <p class="ua-card-main-content"><?php the_content(); ?></p>
                        <?php
                        $endereco = get_post_meta(get_the_ID(), 'endereco', true);
                        $telefone = get_post_meta(get_the_ID(), 'telefone', true);
                        $teleconsulta = get_post_meta(get_the_ID(), 'teleconsulta', true);
                        $telepediatria = get_post_meta(get_the_ID(), 'telepediatria', true);
                        ?>
                        <p class="ua-card-address">

                            <?php if ($endereco) { ?>
                                <strong><span class="dashicons dashicons-location"></span> Endereço: </strong>
                                <?php echo esc_html($endereco); ?><br>
                            <?php } ?>

                            <?php if ($telefone) { ?>
                                <strong><span class="dashicons dashicons-phone"></span> Telefone: </strong>
                                <?php echo esc_html($telefone); ?><br>
                            <?php } ?>

                            <?php if ($teleconsulta) { ?>
                                <strong><span class="dashicons dashicons-desktop"></span> Teleconsulta: </strong> Sim<br>
                            <?php } ?>

                            <?php if ($telepediatria) { ?>
                                <strong><span class="dashicons dashicons-desktop"></span> Telepediatria: </strong> Sim
                            <?php } ?>

                        </p>
                    </div>
                    <div>
                        <a class="ua-card-link" href="<?php echo esc_url(get_post_meta(get_the_ID(), 'link', true)); ?>" target="_blank">Saiba mais <div class="ua-card-link-icon"><span class="dashicons dashicons-admin-links"></span></div></a>
                    </div>
                </div>
                <?php if (has_post_thumbnail()) the_post_thumbnail(); ?>
                <?php if (!has_post_thumbnail()) { ?>
                    <div class="ua-card-placeholder">
                        <span class="ua-placeholder-legend">Sem imagem</span>
                    </div>
                <?php } ?>
            </div>
        <?php endwhile;

        wp_reset_postdata(); ?>
    </div>
<?php return ob_get_clean();
});
