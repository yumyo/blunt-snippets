<?php 

  /*
    Plugin Name: Blunt Snippets
    Plugin URI: https://github.com/Hube2/blunt-snippets
    Description: Allows adding of any code snippets (HTML, JS, CSS, PHP, whatever) to content and widgets using shortcodes. Requires that both ACF installed and active.
    Author: John A. Huebner II
    Author URI: https://github.com/Hube2
    Version: 0.0.1
  */
  
  new bluntSnippets();
  
  class bluntSnippets {
    
    private $post_type = 'blunt-snippets';
    private $label = 'Code Snippet';
    
    public function __construct() {
      register_activation_hook(__FILE__, array($this, 'activate'));
      register_deactivation_hook(__FILE__, array($this, 'deactivate'));
      add_action('init', array($this, 'register_post_type'));
      add_filter('manage_edit-'.$this->post_type.'_columns', array($this, 'admin_columns'));
      add_action('manage_'.$this->post_type.'_posts_custom_column', array($this, 'admin_columns_content'), 10, 2 );  
      add_action('acf/register_fields', array($this, 'acf_register_fields'));
      add_shortcode('blunt-snippet', array($this, 'do_shortcode'));
      if (!has_filter('widget_text', 'do_shortcode')) {
        add_filter('widget_text', 'do_shortcode');
      }
    } // end public function __construct
    
    public function do_shortcode($attributes) {
      if (isset($attributes['id'])) {
        $post_id = $attributes['id'];
        $active = get_post_meta($post_id, '_blunt_snippet_active', true);
        if ($active) {
          $content = get_post_meta($post_id, '_blunt_snippet', true);
          if (strpos($content, "<"."?php") !== false) {
            ob_start();
            eval("?".">".$content);
            $content = ob_get_clean();
          } // end if php
          $content = do_shortcode($content);
        } // end if active
      } // end if id
      return $content;
    } // end public function do_shortcode
    
    public function acf_register_fields() {
      if (!function_exists('register_field_group')) {
        return;
      }
      $field_group = array('id' => 'acf_bcs-details',
                           'title' => 'Code Snippet Details',
                           'fields' => array(array('key' => '_acf_bcs_snippet_message',
                                                   'label' => 'Code Snippets Message',
                                                   'name' => '',
                                                   'type' => 'message',
                                                   'message' => 'Code snippets allows you to add whatever code '.
                                                                 'you would like to content areas and widgets on '.
                                                                'your site. You can include HTML, JavaScript, '.
                                                                'CSS or even PHP.'."\r\n\r\n".
                                                                'All code must be enclosed in the correct tags '.
                                                                'as all code you enter is considered to be HTML. '.
                                                                'For example if your are adding JavaScript then '.
                                                                'you would enclose it in &lt;script&gt;'.
                                                                '&lt;/script&gt; tags. If you\'re creating PHP '.
                                                                'then you\'d use the proper PHP tags (&lt;?php '.
                                                                '?&gt;)'."\r\n\r\n".'Note that no syntax '.
                                                                'checking is done on the code you enter. It '.
                                                                'is your responsibility to ensure it is '.
                                                                'working code. You should test the code before '.
                                                                'inserting in on your site.'."\r\n\r\n".'After '.
                                                                'you save the code copy the shortcode value for '.
                                                                'this snippet and paste it into your content or '.
                                                                'a widget to place the code wherever you need it on '.
                                                                'your site.'),
                                             array('key' => '_acf_bcs_snippet_active',
                                                   'label' => 'Active',
                                                   'name' => '_blunt_snippet_active',
                                                   'type' => 'radio',
                                                   'instructions' => 'Is this snippet active. Marking a '.
                                                                     'code snippet inactive will remove it '.
                                                                     'from your site without needing to '.
                                                                     'find and remove the code wherever it '.
                                                                     'is inserting on your site.',
                                                   'choices' => array(1 => 'Yes',
                                                                      0 => 'No',),
                                                   'other_choice' => 0,
                                                   'save_other_choice' => 0,
                                                   'default_value' => 1,
                                                   'layout' => 'horizontal'),
                                             array('key' => '_acf_bcs_snippet',
                                                   'label' => 'Code Snippet',
                                                   'name' => '_blunt_snippet',
                                                   'type' => 'textarea',
                                                   'instructions' => 'Enter your code snippet.',
                                                   'default_value' => '',
                                                   'formatting' => 'none')),
                           'location' => array(array(array('param' => 'post_type',
                                                           'operator' => '==',
                                                           'value' => 'blunt-snippets',
                                                           'order_no' => 0,
                                                           'group_no' => 0))),
                           'options' => array('position' => 'acf_after_title',
                                              'layout' => 'default',
                                              'hide_on_screen' => array(0 => 'permalink',
                                                                        1 => 'the_content',
                                                                        2 => 'excerpt',
                                                                        3 => 'custom_fields',
                                                                        4 => 'discussion',
                                                                        5 => 'comments',
                                                                        6 => 'slug',
                                                                        7 => 'author',
                                                                        8 => 'format',
                                                                        9 => 'featured_image',
                                                                        10 => 'categories',
                                                                        11 => 'tags',
                                                                        12 => 'send-trackbacks')),
                           'menu_order' => 0);
      register_field_group($field_group);
    } // end public function acf_register_fields
    
    public function admin_columns($columns) {
      $new_columns = array();
      foreach ($columns as $index => $column) {
        if (strtolower($column) == 'title') {
          $new_columns[$index] = $column;
          $new_columns['activesnippet'] = __('Active');
          $new_columns['shortcode'] = __('Shortcode');
        } else {
          if (strtolower($column) != 'date') {
            $new_columns[$index] = $column;
          }
        }
      }
      return $new_columns;
    } // end public function admin_columns
    
    public function admin_columns_content($column_name, $column_id) {
      global $post;
      $post_id = $post->ID;
      switch ($column_name) {
        case 'activesnippet':
          $active = get_post_meta($post_id, '_blunt_snippet_active', true);
          if ($active) {
            echo 'Yes';
          } else {
            echo 'No';
          }
          break;
        case 'shortcode':
          //echo 'copy &amp; paste this code: ';
          echo '[blunt-snippet id="',$post_id,'"]';
          break;
        default:
          // do nothing
          break;
      } // end switch
    } // end public function admin_columns_content
    
    public function register_post_type() {
      // register the post type
      $args = array('label' => $this->label.'s',
                    'description' => '',
                    'public' => false,
                    'show_ui' => true,
                    'show_in_menu' => true,
                    'capability_type' => 'post',
                    'map_meta_cap' => true,
                    'hierarchical' => false,
                    'rewrite' => array('slug' => $this->post_type, 'with_front' => true),
                    'query_var' => true,
                    'exclude_from_search' => true,
                    'menu_position' => 100,
                    'supports' => array('title', 'custom-fields', 'revisions'),
                    'labels' => array ('name' => $this->label.'s',
                                       'singular_name' => $this->label,
                                       'menu_name' => $this->label.'s',
                                       'add_new' => 'Add '.$this->label,
                                       'add_new_item' => 'Add New '.$this->label,
                                       'edit' => 'Edit',
                                       'edit_item' => 'Edit '.$this->label,
                                       'new_item' => 'New '.$this->label,
                                       'view' => 'View '.$this->label,
                                       'view_item' => 'View '.$this->label,
                                       'search_items' => 'Search '.$this->label.'s',
                                       'not_found' => 'No '.$this->label.'s Found',
                                       'not_found_in_trash' => 'No '.$this->label.'s Found in Trash',
                                       'parent' => 'Parent '.$this->label));
      register_post_type($this->post_type, $args);
    } // end public function register_post_type
    
    public function activate() {
      // just in case I want to do anything on activate
    } // end public function activate
    
    public function deactivate() {
      // just in case I want to do anyting on deactivate
    } // end public function deactivate
    
  } // end class bluntSnippets


?>