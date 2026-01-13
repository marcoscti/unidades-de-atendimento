<?php

/**
 * Plugin Name: Unidades de Atendimento
 * Description: CRUD de Unidades de Atendimento com shortcode para exibição em cards filtráveis.
 * Version: 1.1.1
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
<?php
}

add_action('save_post', function ($post_id) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    foreach (['endereco', 'telefone', 'link', 'tipo', 'ativo'] as $field) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, $field, $_POST[$field]);
        } else {
            delete_post_meta($post_id, $field);
        }
    }
});

add_shortcode('unidades_atendimento', function () {
    wp_enqueue_style('ua-style', UA_PLUGIN_URL . 'assets/style.css', [], "1.1.1", true);
    wp_enqueue_script('ua-script', UA_PLUGIN_URL . 'assets/script.js', [], "1.1.1", true);

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
        <button data-filter="Todos">Todos</button>
        <button data-filter="Hospital">Hospital</button>
        <button data-filter="UPA">UPA</button>
        </div>
        <div class="ua-count-itens">
        <strong>Unidades encontradas: </strong><span id="ua-total-count"><?php echo $q->found_posts; ?></span>
        </div>
    </div>
    <div class="ua-cards">
        <?php while ($q->have_posts()): $q->the_post();
            $tipo = get_post_meta(get_the_ID(), 'tipo', true); ?>
            <div class="ua-card" data-tipo="<?php echo esc_attr($tipo); ?>">
                <div class="ua-card-content">
                    <div>
                        <h3 class="ua-card-title"><?php the_title(); ?></h3>
                        <p><?php the_content(); ?></p>
                        <p><strong>Endereço: </strong><?php echo esc_html(get_post_meta(get_the_ID(), 'endereco', true)); ?>
                            <br><strong>Telefone: </strong><?php echo esc_html(get_post_meta(get_the_ID(), 'telefone', true)); ?>
                        </p>
                    </div>
                    <div>
                        <a class="ua-card-link" href="<?php echo esc_url(get_post_meta(get_the_ID(), 'link', true)); ?>" target="_blank">Saiba mais <div class="ua-card-link-icon"><svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                    viewBox="0 0 48 48" style="enable-background:new 0 0 48 48;" xml:space="preserve">
                                    <path style="fill:none;stroke:#FFFFFF;stroke-width:3;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;" d="
	M38.5,25.5v10c0,3.314-2.686,6-6,6h-20c-3.314,0-6-2.686-6-6v-20c0-3.314,2.686-6,6-6h10" />
                                    <line style="fill:none;stroke:#FFFFFF;stroke-width:3;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;" x1="23.5" y1="24.5" x2="41.5" y2="6.5" />
                                    <polyline style="fill:none;stroke:#FFFFFF;stroke-width:3;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;" points="
	27.5,6.5 41.5,6.5 41.5,20.5 " />
                                </svg></div></a>
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
