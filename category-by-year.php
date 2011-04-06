<?php
/*
 * Plugin Name: Category by year widget
 * Version: 1.0
 * Plugin URI: http://www.linusbohman.se/
 * Description: Creates a widget that displays all posts in a category by year if you're on a single post or a category page.
 * Author: Linus Bohman
 */
class catByYear extends WP_Widget
{

  function catByYear(){
    $widget_ops = array('classname' => 'catbyyear',
                        'description' => __("Displays all posts in a category by year if you're on a single post or a category page"));
    
    $control_ops = array('width' => 100, 'height' => 100);
    $this->WP_Widget('catByYear', __('Category by Year'), $widget_ops, $control_ops);
  }

 /*
  * Displays the widget
  */
  function widget($args, $instance){
    $data;
    $header;
    
    if(!empty($instance)) {
      /* Variables */
      global $wpdb;
    }

    /* Print to view */
    if(is_single() || is_category()) {

      if (is_single()) {
          $categories = get_the_category();
          $cat_ID = $categories[0]->cat_ID;
        } elseif (is_category()) {
          $cat_ID = get_query_var('cat');
        } else {
          $cat_ID = '';
        }

      if ($cat_ID) {
        $cat_args = array(
          'cat' => $cat_ID,
          'post__not_in' => array($post->ID),
          'posts_per_page'=>-1,
          'caller_get_posts'=>1
        );
        $cat_query = null;
        $cat_query = new WP_Query($cat_args);

        if($cat_query->have_posts()) {
          echo $args['before_widget'];
          echo $args['before_title']; ?>More <?php echo get_the_category_by_id($cat_ID); ?><?php echo $args['after_title']; ?>
          <ul>
            <?php while ($cat_query->have_posts()) : $cat_query->the_post(); ?>
              <li>
                <?php $current_post_year = get_the_time('Y');
                if ($current_post_year != $past_post_year) { ?>
                  <span class="catbyyear-year"><?php the_time('Y'); ?></span>
                <?php } ?>
                <a href="<?php the_permalink(); ?>" rel="bookmark" title="Visit <?php the_title_attribute(); ?>">
                  <?php the_title(); ?>
                </a>
              </li>
            <?php $past_post_year = get_the_time('Y');
            endwhile; ?>
          </ul>
          <?php echo $args['after_widget'];
        }
      }
      wp_reset_query();
    }
  }

 /**
  * Saves the widget settings
  */
  function update($new_instance, $old_instance){
    $instance = $old_instance;
    return $instance;
  }
  
 /**
  * Form for admin
  */
  function form($instance) {
  }
} /* End of class */


 /**
  * Register Widget
  */
  function catByYearInit() {
    register_widget('catByYear');
  }

add_action('widgets_init', 'catByYearInit');