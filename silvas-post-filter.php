<?php
/*
Plugin Name: Silvas Post Filter
Plugin URI:
Description: Silvas Post Filter.
Version: 1.0
Author: Silvas Development & Technology
Author URI: http://www.silvas.nl
License: Commercial
*/

function post_filter()
{
    global $post, $wpdb;
    $parrent_terms = array();
    $children_terms = array();
    $postArgs = array();
    $lastposts = array();
    $category_array = array();
    $termArgs = array();
    $terms = array();
    $array_length = 0;
    $parrent_array_length = 0;
    $children_array_length = 0;
    $class_list = "";
    $category_name = "";

    $termArgs = array(
        'child_of'    	=> 0,
        'parent'      	=> '',
        'orderby'     	=> 'parent',
        'order'       	=> 'DESC',
        'taxonomy'    	=> 'category',
        'hierarchical'	=> true
    );

    $terms = get_categories($termArgs);
    post_filter_scripts();

    echo '<div id="post-filter" class="md-12"><h1>Categorieën</h1><br />';

    foreach($terms as $term)
    {
        if ($term->parent == 0)
        {
            $parrent_terms[$term->name] = array('parent' => $term->parent, 'term_id' => $term->term_id);
        }
    }

    foreach($terms as $term)
    {
        if ($term->parent > 0)
        {
            $children_terms[$term->name] = array('parent' => $term->parent, 'term_id' => $term->term_id);
        }
    }

    foreach($terms as $term)
    {
        if ($term->parent == 0)
        {
            get_term_children($parrent_terms[$term->name]['term_id'], 'category');
        }
    }

    $parrent_array_length = count($parrent_terms);
    $children_array_length = count($children_terms);

    foreach($parrent_terms as $parrent_term => $parrent_term_value)
    {
        echo '<div><h2 id="category-' . strtolower(str_replace(' ', '-', $parrent_term)) . '"  value="' . $parrent_term . '" style="font-size: 2em !important;">' . $parrent_term . '</h2></div><br />';
        echo '
<div style="width: 100% !important;">
    <div style="display: inline-block !important;">';
        foreach($children_terms as $children_term => $children_term_value)
        {
            $str = (string)$children_term_value;

            if($children_term_value['parent'] == $parrent_term_value['term_id'])
            {
                echo '<input id="category-' . strtolower(str_replace(' ', '-', $children_term)) . '" type="button" class="btn btn-success" value="' . $children_term . '" onclick="filterPosts(this.id);" focus="true" style="font-size: 1em !important; margin: 0px 5px 5px 0px;">';
            }
        }
        echo '</div></div><br />';
    }

    echo '<script type="text/javascript" src="' . plugins_url('js/post-filter.js', __FILE__) . '"></script></div>';

    $postArgs = array('posts_per_page' => 10);
    $lastposts = get_posts($postArgs);

    foreach($lastposts as $post)
    {
        setup_postdata($post);
        $array_length = count(wp_get_post_categories($post->ID, array('post_type' => 'post', 'fields' => 'all')));
        $category_array = wp_get_post_categories($post->ID, array('post_type' => 'post', 'fields' => 'all'));

        for($i = 0; $i < $array_length; $i++)
        {
            if(($i + 1) < $array_length)
            {
                $category_name = strtolower(str_replace(' ', '-', $category_array[$i]->name));
                $class_list .= strtolower('category-' . $category_name . ' ');
            }
            else
            {
                $category_name = strtolower(str_replace(' ', '-', $category_array[$i]->name));
                $class_list .= strtolower('category-' . $category_name);
            }
        }?>
<div class="media <?php echo $class_list ?>" style="display: block;">
    <div class="media-left media-middle">
        <a href="<?php the_permalink(); ?>">
            <img class="media-object" src="data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz48c3ZnIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgd2lkdGg9IjMwMCIgaGVpZ2h0PSIzMDAiIHZpZXdCb3g9IjAgMCAzMDAgMzAwIj48cGF0aCBmaWxsPSIjNUZDNkMzIiBkPSJNMzAwIDE1MGMwIDEuNSAwIDMtLjEgNC40LS4xIDIuOC0uMiA1LjYtLjUgOC40IDAgLjMtLjEuNy0uMSAxLS4xLjctLjEgMS40LS4yIDIuMS02LjUgNjEuOC01MC42IDExMi41LTEwOSAxMjguNi0uNy4yLTEuNS40LTIuMi42LTEwLjYgMi44LTIxLjggNC40LTMzLjIgNC44LTEuNi4xLTMuMS4xLTQuNy4xLTQuNSAwLTguOS0uMi0xMy4zLS42QzYwLjEgMjkyLjcgMCAyMjguNCAwIDE1MCAwIDY3LjIgNjcuMSAwIDE1MCAwYzgyLjggMCAxNTAgNjcuMiAxNTAgMTUweiIvPjxwYXRoIGZpbGw9IiM0NjkyOEUiIGQ9Ik0yOTkuMSAxNjUuOWMtNi41IDYxLjgtNTAuNiAxMTIuNS0xMDkgMTI4LjYtLjcuMi0xLjUuNC0yLjIuNmwtNDEuMy00MS4zIDM3LTIwMy41IDExNS41IDExNS42eiIvPjxwYXRoIGZpbGw9IiM1RkM2QzMiIGQ9Ik0xODQgMjEzLjh2LTE1NmgtNzEuNnYxNTZIMTg0eiIvPjxwYXRoIGZpbGw9IiNBMURBRDkiIGQ9Ik0xMTIuNCAyMDMuNVYxMDAuNGMwLTEuOSAxLjYtMy41IDMuNS0zLjVoNjQuN2MxLjkgMCAzLjUgMS42IDMuNSAzLjV2MTAzLjFoLS4xTDE2OSAxODZsLS4yLS4yYy0xLjQtMS42LTMuOS0xLjYtNS4zIDBsLS4yLjItMTUgMTcuNWgtLjJsLTE0LjgtMTcuMy0uMi0uMi0uMi0uMmMtMS40LTEuNi0zLjktMS42LTUuMyAwbC0uMi4yLTE1IDE3LjV6TTExMy40IDgyLjFoNjkuNWMuNiAwIDEuMS41IDEuMSAxLjF2NC43YzAgLjYtLjUgMS4xLTEuMSAxLjFoLTY5LjVjLS42IDAtMS4xLS41LTEuMS0xLjF2LTQuN2MuMS0uNi41LTEuMSAxLjEtMS4xek0xMTMuNCA3MS42aDY5LjVjLjYgMCAxLjEuNSAxLjEgMS4xdjQuN2MwIC42LS41IDEuMS0xLjEgMS4xaC02OS41Yy0uNiAwLTEuMS0uNS0xLjEtMS4xdi00LjdjLjEtLjYuNS0xLjEgMS4xLTEuMXoiLz48cGF0aCBmaWxsPSIjRkZGIiBkPSJNMTE0LjMgNDkuOUgxODJjMS4xIDAgMiAuOSAyIDJ2MTAuN2MwIDEuMS0uOSAyLTIgMmgtNjcuN2MtMS4xIDAtMi0uOS0yLTJWNTEuOWMuMS0xLjEuOS0yIDItMnpNMTMzLjMgMjM4LjNIMTYzbDIxLTI0LjQtMTUuNi0xOC4yYy0xLjItMS40LTMuNC0xLjQtNC42IDBsLTEzLjMgMTUuNWMtMS4yIDEuNC0zLjQgMS40LTQuNiAwbC0xMy4zLTE1LjVjLTEuMi0xLjQtMy40LTEuNC00LjYgMGwtMTUuNiAxOC4yIDIwLjkgMjQuNHoiLz48cGF0aCBmaWxsPSIjQTFEQUQ5IiBkPSJNMTUwIDI1My41bDEzLTE1LjJoLTI5LjdsMTMgMTUuMmMxIDEuMSAyLjcgMS4xIDMuNyAweiIvPjxwYXRoIGZpbGw9IiNGRkYiIGQ9Ik0xNjMuMyAxODZWOTYuOWg1LjZWMTg2Yy0xLjUtMS43LTQuMi0xLjctNS42IDB6Ii8+PGc+PHBhdGggZmlsbD0iI0ZGRiIgZD0iTTEyNy40IDE4NlY5Ni45aDUuNlYxODZsLS4yLS4yYy0xLjQtMS42LTMuOS0xLjYtNS4zIDBsLS4xLjJ6Ii8+PC9nPjwvc3ZnPg==" alt="<?php the_title(); ?>" style="max-width: none !important; width: 86px !important; height: 86px !important;">
        </a>
    </div>
    <div class="media-body">
        <h4 class="media-heading"><?php the_title(); ?></h4>
        <?php the_content(); ?>
        <p><small><?php echo get_the_date(); ?> / <?php the_category( ', ' ); ?> / <?php the_author(); ?></small></p>
    </div>
</div><?php

        $class_list = "";
    }

    wp_reset_postdata();
}

function post_filter_scripts()
{
    wp_enqueue_script('jquery');
    wp_enqueue_style('bootstrap', plugins_url('css/bootstrap.min.css', __FILE__), array(), '3.3.7', 'all');
    wp_enqueue_script('bootstrap-js', plugins_url('js/bootstrap.min.js', __FILE__), array('jquery'), '3.3.7', true);
}
add_action('wp_enqueue_scripts', 'post_filter_scripts');

// [showpostfilter]
function post_filter_func($atts)
{
    return post_filter();
}
add_shortcode('showpostfilter', 'post_filter_func');