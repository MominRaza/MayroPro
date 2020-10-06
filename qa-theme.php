<?php

class qa_html_theme extends qa_html_theme_base
{
	private $nav_bar_avatar_size = 52;

	public function head_metas()
	{
		$this->output('<meta name="viewport" content="width=device-width, initial-scale=1"/>');
		parent::head_metas();
	}

	public function head_css()
	{
		$this->content['css_src'][] = $this->rooturl . 'icons/material-icons.css';
		$this->output('<link rel="manifest" href="'. $this->rooturl .'manifest.webmanifest">');
		parent::head_css();
	}

	public function head_script()
	{
		$jsUrl = $this->rooturl . 'js/main.js?' . QA_VERSION;
		$this->content['script'][] = '<script src="' . $jsUrl . '"></script>';

		parent::head_script();
	}

	// Adding point count for logged in user
	public function logged_in()
	{
		parent::logged_in();
		if (qa_is_logged_in()) {
			$userpoints = qa_get_logged_in_points();
			$pointshtml = $userpoints == 1
				? qa_lang_html_sub('main/1_point', '1', '1')
				: qa_html(qa_format_number($userpoints))
			;
			$this->output('<div class="qam-logged-in-points">' . $pointshtml . '</div>');
		}
	}

	public function body_content()
	{
		$this->body_prefix();
		$this->notices();

		$extratags = isset($this->content['wrapper_tags']) ? $this->content['wrapper_tags'] : '';
		$this->output('<div class="qa-body-wrapper"' . $extratags . '>', '');

		$this->widgets('full', 'top');
		$this->header();
		$this->widgets('full', 'high');

		switch ( $this->template ) {
			case 'user-wall' :
            case 'messages' :
			case 'message' :
				$this->output('<div class="qam-main-sidepanel qam-user-wall-message">');
			break;
			case 'admin' :
				$type = qa_request_part( 1 );
				switch ( $type ) {
					case 'plugins' :
						$this->output('<div class="qam-main-sidepanel qam-admin-plugins">');
						break;
					default :
						$this->output('<div class="qam-main-sidepanel">');
						break;
				}
			break;
			case 'login' :
			case 'register' :
			case 'forgot' :
				$this->output('<div class="qam-main-sidepanel qam-login-register">');
			break;
			default:
				$this->output('<div class="qam-main-sidepanel">');
		}

		$this->main();
		$this->sidepanel();
		$this->output('</div>');

		$this->widgets('full', 'low');
		$this->footer();
		$this->widgets('full', 'bottom');

		$this->output('</div> <!-- END body-wrapper -->');

		$this->body_suffix();
	}

	public function header()
	{
		$this->output('<div class="qa-header">');

		$this->logo();

		$this->output('<i id="menu-toggle" onclick="toggleMenu()" class="material-icons">menu</i>');
		$this->output('<i id="search-toggle"  onclick="toggleSearch()" class="material-icons">search</i>');

		$this->nav_user_search();
		$this->output($this->ask_button());
		$this->nav_main_sub();
		$this->header_clear();

		$this->output('</div> <!-- END qa-header -->', '');
	}

	public function nav_user_search()
	{
		$this->qam_user_account();
		$this->output('<div id="qa-nav-user" onclick="toggleUser()">');
		$this->nav('user');
		$this->output('</div>');
		$this->output('<div id="qa-search">');
		$this->search();
		$this->output('</div>');
	}

	public function nav_main_sub()
	{
		$this->output('<div id="qa-nav-main" onclick="toggleMenu()">');
		$this->logo();
		$this->nav('main');
		$this->output('</div>');
		$this->nav('sub');
	}

	public function search_button($search)
	{
		$this->output('<button type="submit" class="qa-search-button"><i class="material-icons">search</i></button>');
	}

	private function qam_user_account()
	{
		if (qa_is_logged_in()) {
			// get logged-in user avatar
			$handle = qa_get_logged_in_user_field('handle');

			if (QA_FINAL_EXTERNAL_USERS)
				$tobar_avatar = qa_get_external_avatar_html(qa_get_logged_in_user_field('userid'), $this->nav_bar_avatar_size, true);
			else {
				$tobar_avatar = qa_get_user_avatar_html(
					qa_get_logged_in_user_field('flags'),
					qa_get_logged_in_user_field('email'),
					$handle,
					qa_get_logged_in_user_field('avatarblobid'),
					qa_get_logged_in_user_field('avatarwidth'),
					qa_get_logged_in_user_field('avatarheight'),
					$this->nav_bar_avatar_size,
					false
				);
			}

			$avatar = strip_tags($tobar_avatar, '<img>');
		}
		else {
			// display login icon and label
			$avatar = '<i class="material-icons">person</i>';
		}

		// finally output avatar with div tag
		$this->output(
			'<div id="user-toggle" onclick="toggleUser()">',
			$avatar,
			'</div>'
		);
	}

	private function ask_button()
	{
		return
			'<div class="qam-ask">' .
			'<a href="' . qa_path('ask', null, qa_path_to_root()) . '" class="qam-ask-link">' .
			'<i class="material-icons">edit</i>'.
			qa_lang_html('main/nav_ask') .
			'</a>' .
			'</div>';
	}

	public function q_list_item($q_item)
	{
		switch ( $this->template ) {
			case 'user-questions' :
            case 'user-answers' :
				$this->output('<div class="qam-q-a-a qa-q-list-item' . rtrim(' ' . @$q_item['classes']) . '" ' . @$q_item['tags'] . '>');
			break;
            case 'user-activity' :
				$this->output('<div class="qam-q-a-a a qa-q-list-item' . rtrim(' ' . @$q_item['classes']) . '" ' . @$q_item['tags'] . '>');
			break;
			default:
				$this->output('<div class="qa-q-list-item' . rtrim(' ' . @$q_item['classes']) . '" ' . @$q_item['tags'] . '>');
		}

		$this->q_item_main($q_item);
		$this->q_item_stats($q_item);
		$this->q_item_clear();

		$this->output('</div> <!-- END qa-q-list-item -->', '');
	}

	public function q_item_main($q_item)
	{
		$this->output('<div class="qa-q-item-main">');
		$this->post_avatar_meta($q_item, 'qa-q-item');
		$this->q_item_title($q_item);
		$this->q_item_content($q_item);
		$this->post_tags($q_item, 'qa-q-item');
		$this->q_item_buttons($q_item);

		$this->output('</div>');
	}

	public function q_item_stats($q_item)
	{
		$this->output('<div class="qa-q-item-stats">');

		$this->voting($q_item);
		$this->view_count($q_item);
		$this->a_count($q_item);

		$this->output('</div>');
	}

	public function post_avatar_meta($post, $class, $avatarprefix = null, $metaprefix = null, $metaseparator = '<br/>')
	{
		$this->output('<div class="qam-q-post-meta">');
		$this->output('<span class="' . $class . '-avatar-meta">');
		$this->avatar($post, $class, $avatarprefix);
		$this->post_meta($post, $class, $metaprefix, $metaseparator);
		$this->output('</span>');
		$this->output('</div>');
	}

	public function q_view($q_view)
	{
		if (!empty($q_view)) {
			$this->output('<div class="qa-q-view' . (@$q_view['hidden'] ? ' qa-q-view-hidden' : '') . rtrim(' ' . @$q_view['classes']) . '"' . rtrim(' ' . @$q_view['tags']) . '>');

			$this->q_view_main($q_view);
			$this->q_view_clear();

			$this->output('</div> <!-- END qa-q-view -->', '');
		}
	}

	public function q_view_main($q_view)
	{
		$this->output('<div class="qa-q-view-main">');

		$this->post_avatar_meta($q_view, 'qa-q-view');
		$this->q_view_content($q_view);
		$this->q_view_extra($q_view);
		$this->q_view_follows($q_view);
		$this->q_view_closed($q_view);
		$this->post_tags($q_view, 'qa-q-view');
		$this->output('<div class="qam-stats-buttons">');
			if (isset($q_view['main_form_tags'])) {
				$this->output('<form ' . $q_view['main_form_tags'] . '>'); // // form for question voting buttons
			}
			$this->q_view_stats($q_view);
			if (isset($q_view['main_form_tags'])) {
				$this->form_hidden_elements(@$q_view['voting_form_hidden']);
				$this->output('</form>');
			}
			$this->view_count($q_view);
			if (isset($q_view['main_form_tags'])) {
				$this->output('<form ' . $q_view['main_form_tags'] . '>'); // form for buttons on question
			}
			$this->q_view_buttons($q_view);
			if (isset($q_view['main_form_tags'])) {
				$this->form_hidden_elements(@$q_view['buttons_form_hidden']);
				$this->output('</form>');
			}
		$this->output('</div>');

		$this->c_list(@$q_view['c_list'], 'qa-q-view');
		$this->c_form(@$q_view['c_form']);

		$this->output('</div> <!-- END qa-q-view-main -->');
	}

	public function c_list_item($c_item)
	{
		$extraclass = @$c_item['classes'] . (@$c_item['hidden'] ? ' qa-c-item-hidden' : '');

		$this->output('<div class="qa-c-list-item ' . $extraclass . '" ' . @$c_item['tags'] . '>');

		$this->c_item_main($c_item);
		$this->c_item_clear();

		$this->output('</div> <!-- END qa-c-item -->');
	}

	public function c_item_main($c_item)
	{
		$this->error(@$c_item['error']);

		$this->post_avatar_meta($c_item, 'qa-c-item');
		if (isset($c_item['expand_tags']))
			$this->c_item_expand($c_item);
		elseif (isset($c_item['url']))
			$this->c_item_link($c_item);
		else
			$this->c_item_content($c_item);
		
		$this->output('<div class="qam-stats-buttons">');
			if (isset($c_item['vote_view']) && isset($c_item['main_form_tags'])) {
				// form for comment voting buttons
				$this->output('<form ' . $c_item['main_form_tags'] . '>');
				$this->voting($c_item);
				$this->form_hidden_elements(@$c_item['voting_form_hidden']);
				$this->output('</form>');
			}
			if (isset($c_item['main_form_tags'])) {
				$this->output('<form ' . $c_item['main_form_tags'] . '>'); // form for buttons on comment
			}
			$this->c_item_buttons($c_item);
			if (isset($c_item['main_form_tags'])) {
				$this->form_hidden_elements(@$c_item['buttons_form_hidden']);
				$this->output('</form>');
			}
		$this->output('</div>');
	}

	public function a_list_item($a_item)
	{
		$extraclass = @$a_item['classes'] . ($a_item['hidden'] ? ' qa-a-list-item-hidden' : ($a_item['selected'] ? ' qa-a-list-item-selected' : ''));

		$this->output('<div class="qa-a-list-item ' . $extraclass . '" ' . @$a_item['tags'] . '>');

		$this->a_item_main($a_item);
		$this->a_item_clear();

		$this->output('</div> <!-- END qa-a-list-item -->', '');
	}

	public function a_item_main($a_item)
	{
		$this->output('<div class="qa-a-item-main">');

		$this->post_avatar_meta($a_item, 'qa-a-item');
		
		if ($a_item['hidden'])
			$this->output('<div class="qa-a-item-hidden">');
		elseif ($a_item['selected'])
			$this->output('<div class="qa-a-item-selected">');
		if (isset($a_item['main_form_tags'])) {
			$this->output('<form ' . $a_item['main_form_tags'] . '>'); // form for buttons on answer
		}
			$this->a_selection($a_item);
		if (isset($a_item['main_form_tags'])) {
			$this->form_hidden_elements(@$a_item['buttons_form_hidden']);
			$this->output('</form>');
		}
		$this->error(@$a_item['error']);
		$this->a_item_content($a_item);

		if ($a_item['hidden'] || $a_item['selected'])
			$this->output('</div>');

		$this->output('<div class="qam-stats-buttons">');
			if (isset($a_item['main_form_tags'])) {
				$this->output('<form ' . $a_item['main_form_tags'] . '>'); // form for answer voting buttons
			}
			$this->voting($a_item);
			if (isset($a_item['main_form_tags'])) {
				$this->form_hidden_elements(@$a_item['voting_form_hidden']);
				$this->output('</form>');
			}	
			if (isset($a_item['main_form_tags'])) {
				$this->output('<form ' . $a_item['main_form_tags'] . '>'); // form for buttons on answer
			}
			$this->a_item_buttons($a_item);
			if (isset($a_item['main_form_tags'])) {
				$this->form_hidden_elements(@$a_item['buttons_form_hidden']);
				$this->output('</form>');
			}

		$this->output('</div>');

		

		$this->c_list(@$a_item['c_list'], 'qa-a-item');
		$this->c_form(@$a_item['c_form']);

		$this->output('</div> <!-- END qa-a-item-main -->');
	}

	public function a_selection($post)
	{
		$this->output('<div class="qa-a-selection">');

		if (isset($post['select_tags'])){
			$this->post_hover_button($post, 'select_tags', '', 'qa-a-select');
			$this->output('<i class="material-icons select">done</i>');
		}
		elseif (isset($post['unselect_tags'])){
			$this->post_hover_button($post, 'unselect_tags', '', 'qa-a-unselect');
			// $this->output('<i class="material-icons unselect">close</i>');
		}
		elseif ($post['selected'])
			$this->output('<div class="qa-a-selected">&nbsp;</div>');

		if (isset($post['select_text']))
			$this->output('<i class="material-icons selected">done_all</i>');

		$this->output('</div>');
	}
	
	// changed post_avatar_meta and message_content order
	public function message_item($message)
	{
		$this->output('<div class="qa-message-item" ' . @$message['tags'] . '>');
		$this->post_avatar_meta($message, 'qa-message');
		$this->message_content($message);
		$this->message_buttons($message);
		$this->output('</div> <!-- END qa-message-item -->', '');
	}

	public function voting_inner_html($post)
	{
		$this->vote_buttons($post);
		$this->vote_clear();
	}

	public function vote_buttons($post)
	{
		$this->output('<div class="qa-vote-buttons ' . (($post['vote_view'] == 'updown') ? 'qa-vote-buttons-updown' : 'qa-vote-buttons-net') . '">');

		switch (@$post['vote_state']) {
			case 'voted_up':
				$this->post_hover_button($post, 'vote_up_tags', '+', 'qa-vote-one-button qa-voted-up');
				$this->output('<i class="material-icons voted_up">thumb_up_alt</i>');
				$this->output_split($post['upvotes_view'], 'selected qa-upvote-count');

				$this->post_disabled_button($post, 'vote_down_tags', '', 'qa-vote-second-button qa-vote-down');
				$this->output('<i class="material-icons">thumb_down_alt</i>');
				$this->output_split($post['downvotes_view'], 'disabled qa-downvote-count');
				break;

			case 'voted_down':
				$this->post_disabled_button($post, 'vote_up_tags', '', 'qa-vote-first-button qa-vote-up');
				$this->output('<i class="material-icons">thumb_up_alt</i>');
				$this->output_split($post['upvotes_view'], 'disabled qa-upvote-count');

				$this->post_hover_button($post, 'vote_down_tags', '&ndash;', 'qa-vote-one-button qa-voted-down');
				$this->output('<i class="material-icons voted_down">thumb_down_alt</i>');
				$this->output_split($post['downvotes_view'], 'selected qa-downvote-count');
				break;

			case 'up_only':
				$this->post_hover_button($post, 'vote_up_tags', '+', 'qa-vote-first-button qa-vote-up');
				$this->output('<i class="material-icons up_only">thumb_up_alt</i>');
				$this->output_split($post['upvotes_view'], 'enabled qa-upvote-count');
				break;

			case 'enabled':
				$this->post_hover_button($post, 'vote_up_tags', '+', 'qa-vote-first-button qa-vote-up');
				$this->output('<i class="material-icons enabled">thumb_up_alt</i>');
				$this->output_split($post['upvotes_view'], 'enabled qa-upvote-count');

				$this->post_hover_button($post, 'vote_down_tags', '&ndash;', 'qa-vote-second-button qa-vote-down');
				$this->output('<i class="material-icons enabled">thumb_down_alt</i>');
				$this->output_split($post['downvotes_view'], 'enabled qa-downvote-count');
				break;

			default:
				$this->post_disabled_button($post, 'vote_up_tags', '', 'qa-vote-first-button qa-vote-up');
				$this->output('<i class="material-icons">thumb_up_alt</i>');
				$this->output_split($post['upvotes_view'], 'disabled qa-upvote-count');

				$this->post_disabled_button($post, 'vote_down_tags', '', 'qa-vote-second-button qa-vote-down');
				$this->output('<i class="material-icons">thumb_down_alt</i>');
				$this->output_split($post['downvotes_view'], 'disabled qa-downvote-count');
				break;
		}

		$this->output('</div>');
	}

	public function favorite_button($tags, $class)
	{
		if (isset($tags)){
			$this->output('<input ' . $tags . ' type="submit" value="" class="' . $class . '-button"/> ');
			if($class == 'qa-favorite'){
				$this->output('<i class="material-icons qa-favorite">star_border</i>');
			}else{
				$this->output('<i class="material-icons qa-unfavorite">star</i>');
			}
		}
	}

	public function search_field($search)
	{
		$this->output('<input type="text" ' .'placeholder="' . $search['button_label'] . '..." ' . $search['field_tags'] . ' value="' . @$search['value'] . '" class="qa-search-field"/>');
	}

	/**
	 * Mayro Theme attribution.
	 * I'd really appreciate you displaying this link on your Q2A site. Thank you - Momin Raza
	 */
	public function attribution()
	{
		$this->output(
			'<div class="qa-attribution">',
			'<a href="https://github.com/MominRaza/Mayro">Mayro Theme</a> <i class="material-icons md-18" title="Developed">code</i> with <i class="material-icons md-18" title="Love">favorite</i> by <a href="https://github.com/MominRaza">Momin Raza</a>',
			'</div>'
		);
		parent::attribution();
	}
}
?>
