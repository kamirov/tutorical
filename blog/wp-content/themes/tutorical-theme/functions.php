<?

function base_url($path = '')
{
	$base_url = 'http://tutorical.com/';
//	$base_url = 'http://localhost/Software/Tutorical/trunk/';

	return $base_url.$path;
}

function get_author_box($include_link_to_posts = TRUE)
{
    if ($description = get_the_author_meta('description'))
    {
        $avatar_box = '
            <div class="author-info boxes">
                <div class="author-avatar">
        ';

        $avatar_box .= get_avatar_element();

        $avatar_box .= '
                </div>
                <div class="author-description">
                    <h2>About '.t_get_author($include_link_to_posts).'</h2>
                    <p>'.nl2br(get_the_author_meta('description')).'</p>
                </div>
            </div>
        ';

        return $avatar_box;
    }
    return;
}

function t_get_author($include_link_to_posts)
{
    $aid = get_the_author_meta('ID');
    $profile_link = get_the_author_meta('url');

    $output = '';

    if ($include_link_to_posts)
    {
        $output = '<a href="'.get_author_posts_url($aid).'" title="See more posts by '.get_the_author().'">'.get_the_author().'</a>';
    }
    else
    {
        $output = get_the_author();        
    }

    if ($profile_link)
    {
        $output .= ' <span class="author-profile-links">(<a target="_blank" href="'.$profile_link.'">Tutorical Profile</a>)</span>';
    }

    return $output;
}

function get_avatar_element()
{
    // First use the Tutorical avatar, if there's a Tutorical ID
    $tutorical_user_id = get_the_author_meta('tutorical_user_id');
    if ($tutorical_user_id)
        return '<img src="'.base_url('assets/uploads/images/'.$tutorical_user_id.'/avatar.jpg').'">';

    // If no Tutorical avatar, then use the user's gravatar
    $user_email = get_the_author_meta('user_email');
    return get_avatar($user_email);
}

function get_pagination($on_top = FALSE, $include_all = FALSE)
{
    $older = '&laquo; Older Posts';
    $newer = 'Newer Posts &raquo;';

    $prev_link = get_previous_posts_link($newer);
    $next_link = get_next_posts_link($older);

    if(!($prev_link || $next_link))
        return;

    if ($on_top && !$prev_link)
        return;

    // No archive yet, so just set include_all to FALSE
    $include_all = FALSE;

    $pagination = '
        <div class="pagination posts-pagination">
            <div class="button-groups">
    ';

    if ($next_link)
        $pagination .= $next_link; 
    else
        $pagination .= '
            <span class="inactive buttons paginate" title="No older posts">'.$older.'</span>
        ';

    if ($include_all)
        $pagination .= '
            <a class="paginate buttons see-all-articles-button" title="See a list of all posts on the blog" href="'.home_url("/archives").'">All Posts</a>
        ';
            
    if ($prev_link)
        $pagination .= $prev_link; 
    else
        $pagination .= '
            <span class="inactive buttons paginate" title="No newer posts">'.$newer.'</span>
        ';

    $pagination .= '
            </div>
        </div>
    ';

    return $pagination;
}


/* FILTERS
   ======= */


// ---- Tags list title change

// prepare a topic count text
function wp_cloud_title_text($count) {
    return sprintf( _n('%s post', '%s posts', $count), number_format_i18n( $count ) );
}
// customise the tag cloud widget
function my_tag_cloud_args($in){
    return 'topic_count_text_callback=wp_cloud_title_text';
}
add_filter('widget_tag_cloud_args', 'my_tag_cloud_args' );


// ---- Single pagination styles

function filter_next_post_link($link) {
    $link = str_replace("rel=", 'class="paginate buttons" rel=', $link);
    return $link;
}
add_filter('next_post_link', 'filter_next_post_link');

function filter_previous_post_link($link) {
    $link = str_replace("rel=", 'class="paginate buttons" rel=', $link);
    return $link;
}
add_filter('previous_post_link', 'filter_previous_post_link');


// ---- Category pagination styles

function next_posts_link_css($content) { 
    return 'class="next paginate buttons" title="See older posts"';
}
function previous_posts_link_css($content) { 
    return 'class="prev paginate buttons" title="See newer posts"';
}

add_filter('next_posts_link_attributes', 'next_posts_link_css' );
add_filter('previous_posts_link_attributes', 'previous_posts_link_css' );

// ----