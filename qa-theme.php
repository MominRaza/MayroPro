<?php

class qa_html_theme extends qa_html_theme_base
{
	private $nav_bar_avatar_size = 52;

	public function head_metas()
	{
		$this->output('<meta name="viewport" content="width=device-width, initial-scale=1"/>');
		$this->output('<meta name="theme-color" content="#002de3">');
		$this->output('<link rel="manifest" href="'. $this->rooturl .'manifest.webmanifest">');

		$this->output('<link rel="preconnect" href="https://fonts.googleapis.com">');
		$this->output('<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>');
		$this->output('<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">');

		parent::head_metas();
	}

	public function head_css()
	{
		if ($this->isRTL)
			$this->content['css_src'][] = $this->rooturl . 'qa-styles-rtl.min.css?' . QA_VERSION;
		
		parent::head_css();
	}

	public function css_name()
	{
		return 'qa-styles.min.css?' . QA_VERSION;
	}

	public function head_script()
	{
		$jsUrl = $this->rooturl . 'js/main.min.js?' . QA_VERSION;
		$this->content['script'][] = '<script src="' . $jsUrl . '" async></script>';

		parent::head_script();
	}

	public function body_tags()
	{
		$class = 'qa-template-' . qa_html($this->template);
		$class .= empty($this->theme) ? '' : ' qa-theme-' . qa_html($this->theme);

		if (isset($this->content['categoryids'])) {
			foreach ($this->content['categoryids'] as $categoryid) {
				$class .= ' qa-category-' . qa_html($categoryid);
			}
		}

		if (!empty($_COOKIE['theme'])) {
			if ($_COOKIE['theme'] == 'dark') {
				$class .= ' dark-theme';
			} else if ($_COOKIE['theme'] == 'light') {
				$class .= ' light-theme';
			}
		}

		$this->output('class="' . $class . ' qa-body-js-off"');
		
		if ($this->isRTL)
			$this->output('dir="rtl"');
	}

	public function logged_in()
	{
		if (qa_is_logged_in()) {
			$this->output_split(qa_lang_html_sub_split('main/logged_in_x', QA_FINAL_EXTERNAL_USERS
				? qa_get_logged_in_user_html(qa_get_logged_in_user_cache(), qa_path_to_root(), false)
				: qa_get_one_user_html(qa_get_logged_in_handle(), false)
			), 'qa-logged-in', 'div');

			$userpoints = qa_get_logged_in_points();
			$username = qa_get_logged_in_handle();
			$pointshtml = $userpoints == 1
				? qa_lang_html_sub('main/1_point', '1', '1')
				: qa_html(qa_format_number($userpoints))
			;
			$this->output('<ul class="qa-nav-user-list">');
				if ( qa_opt( 'allow_private_messages' ) ) {
					$this->output('<li class="qa-nav-user-item qa-nav-user-messages">');
					if($this->template == 'messages'){
						$this->output('<a href="'.qa_path_html( "messages" ).'" class="qa-nav-user-link qa-nav-user-selected">'.qa_lang_html('misc/nav_user_pms').'</a>');
					}else{
						$this->output('<a href="'.qa_path_html( "messages" ).'" class="qa-nav-user-link">'.qa_lang_html('misc/nav_user_pms').'</a>');
					}
					$this->output('</li>');
				}

				$this->output('<li class="qa-nav-user-item qa-nav-user-points">');
				if($this->template == 'user' && qa_request_part(1) == $username){
					$this->output('<a href="'.qa_path_html( "user/".$username ).'" class="qa-nav-user-link qa-nav-user-selected">'. qa_lang_html_sub('main/x_points', $pointshtml) .'</a>');
				}else{
					$this->output('<a href="'.qa_path_html( "user/".$username ).'" class="qa-nav-user-link">'. qa_lang_html_sub('main/x_points', $pointshtml) .'</a>');
				}
				$this->output('</li>');

				$this->output('<li class="qa-nav-user-item qa-nav-user-favorites">');
				if($this->template == 'favorites'){
					$this->output('<a href="'.qa_path_html( "favorites" ).'" class="qa-nav-user-link qa-nav-user-selected">'.qa_lang_html('misc/nav_my_favorites').'</a>');
				}else{
					$this->output('<a href="'.qa_path_html( "favorites" ).'" class="qa-nav-user-link">'.qa_lang_html('misc/nav_my_favorites').'</a>');
				}
				$this->output('</li>');
			$this->output('</ul>');
		}
		$themeIcon = 'brightness_auto';
		$themeTitle = 'System default';
		if (!empty($_COOKIE['theme'])) {
			if ($_COOKIE['theme'] == 'dark') {
				$themeIcon = 'dark_mode';
				$themeTitle = 'Dark';
			} else if ($_COOKIE['theme'] == 'light') {
				$themeIcon = 'light_mode';
				$themeTitle = 'Light';
			}
		}
	}

	public function body_content()
	{
		$this->body_prefix();

		$extratags = isset($this->content['wrapper_tags']) ? $this->content['wrapper_tags'] : '';
		$this->output('<div class="qa-body-wrapper"' . $extratags . '>', '');

		$this->widgets('full', 'top');
		$this->header();
		$this->widgets('full', 'high');

		switch ( $this->template ) {
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

	public function main()
	{
		$content = $this->content;
		$hidden = !empty($content['hidden']) ? ' qa-main-hidden' : '';

		$this->output('<div class="qa-main' . $hidden . '">');

		$this->notices();
		
		$this->widgets('main', 'top');

		$this->page_title_error();

		$this->widgets('main', 'high');

		$this->main_parts($content);

		$this->widgets('main', 'low');

		$this->page_links();
		$this->suggest_next();

		$this->widgets('main', 'bottom');

		$this->output('</div> <!-- END qa-main -->', '');
	}

	public function header()
	{
		$this->output('<div class="qa-header">');

		$this->logo();

		$this->output('<i id="menu-toggle" onclick="toggleMenu()" class="material-icons">menu</i>');
		$this->output('<i id="search-toggle"  onclick="toggleSearch()" class="material-icons">search</i>');

		
		if (isset($this->content['loggedin']['suffix'])) {
			$this->output($this->content['loggedin']['suffix']);
        }
		
		$this->nav_user_search();
		
		$this->output('<div class="fab">');
			$themeIcon = 'brightness_auto';
			$themeTitle = 'System default';
			if (!empty($_COOKIE['theme'])) {
				if ($_COOKIE['theme'] == 'dark') {
					$themeIcon = 'dark_mode';
					$themeTitle = 'Dark';
				} else if ($_COOKIE['theme'] == 'light') {
					$themeIcon = 'light_mode';
					$themeTitle = 'Light';
				}
			}
			$this->output('<i id="theme-toggle" class="material-icons" onclick="toggleTheme(this)" title="'.$themeTitle.'">'.$themeIcon.'</i>');
		
			switch ( $this->template ) {
				case 'admin' :
				case 'login' :
				case 'register' :
				case 'forgot' :
				case 'ask' :
				case 'message' :
				case 'messages' :
				case 'user' :
				case 'user-wall' :
				case 'users' :
				case 'account' :
				case 'favorites' :
				case 'feedback' :
				break;
				default:
					$this->output($this->ask_button());
			}
		$this->output('</div>');

		$this->nav_main_sub();
		$this->header_clear();

		$this->output('</div> <!-- END qa-header -->', '');
	}

	public function nav_user_search()
	{
		$this->qam_user_account();
		$this->output('<div id="qa-nav-user">');
		$this->nav('user');
		$this->output('<div id="qa-nav-user-clear" onclick="toggleUser()"></div>');
		$this->output('</div>');
		$this->output('<div id="qa-search">');
		$this->search();
		$this->output('</div>');
	}

	public function nav_main_sub()
	{
		$this->output('<div id="qa-nav-main">');
		$this->logo();
		$this->nav('main');
		$this->output('</div>');
		$this->output('<div id="qa-nav-main-clear" onclick="toggleMenu()"></div>');
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
			$avatar = '<div class="login-person"><i class="material-icons">person</i></div>';
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
		$params = null;
		if (isset($this->content['categoryids'])) {
			foreach ($this->content['categoryids'] as $categoryid) {
				$params = array('cat' => qa_html($categoryid));
			}
		}
		return
			'<div class="qam-ask">' .
				'<a href="' . qa_path('ask', $params, qa_path_to_root()) . '" class="qam-ask-link">' .
					'<i class="material-icons">edit</i>'.
					qa_lang_html('main/nav_ask') .
				'</a>' .
			'</div>';
	}

	public function q_list_item($q_item)
	{
		$this->output('<div class="qa-q-list-item' . rtrim(' ' . @$q_item['classes']) . '" ' . @$q_item['tags'] . '>');
		
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

	/**
	 * Add close icon
	 * @param array $q_item
	 */
	public function q_item_title($q_item)
	{
		$closedText = qa_lang('main/closed');
		$imgHtml = empty($q_item['closed'])
			? ''
			: '<span title="' . $closedText . '" class="material-icons md-18">lock</span>';

		$this->output(
			'<div class="qa-q-item-title">',
			// add closed note in title
			'<a href="' . $q_item['url'] . '">' .$imgHtml, $q_item['title'] . '</a>',
			'</div>'
		);
	}

	/**
	 * Add closed icon for closed questions
	 */
	public function title()
	{
		$q_view = isset($this->content['q_view']) ? $this->content['q_view'] : null;

		// link title where appropriate
		$url = isset($q_view['url']) ? $q_view['url'] : false;

		// add closed image
		$closedText = qa_lang('main/closed');
		$imgHtml = empty($q_view['closed'])
			? ''
			: '<span title="' . $closedText . '" class="material-icons">lock</span>';

		if (isset($this->content['title'])) {
			$this->output(
				$url ? '<a href="' . $url . '">' : '',
				$imgHtml,
				$this->content['title'],
				$url ? '</a>' : ''
			);
		}
	}

	public function q_item_stats($q_item)
	{
		$this->output('<div class="qa-q-item-stats">');

		$this->voting($q_item);
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

	public function post_meta($post, $class, $prefix = null, $separator = '<br/>')
	{
		$this->output('<span class="' . $class . '-meta">');

		if (isset($prefix))
			$this->output($prefix);

		$order = Array('who', 'where', 'what', 'when');

		foreach ($order as $element) {
			switch ($element) {
				case 'what':
					$this->post_meta_what($post, $class);
					break;

				case 'when':
					$this->post_meta_when($post, $class);
					break;

				case 'where':
					$this->post_meta_where($post, $class);
					break;

				case 'who':
					$this->post_meta_who($post, $class);
					break;
			}
		}

		$this->view_count($post);
		$this->post_meta_flags($post, $class);

		if (!empty($post['what_2'])) {
			$this->output($separator);
			$this->output('<div class="qam-what-2" onclick="toggleExtra(this)">');
				$this->output('<i class="material-icons md-18">info_outline</i>');
				$this->output('<div class="qam-what-2-body">');
				foreach ($order as $element) {
					switch ($element) {
						case 'what':
							$this->output('<span class="' . $class . '-what">' . $post['what_2'] . '</span>');
							break;

						case 'when':
							$this->output_split(@$post['when_2'], $class . '-when');
							break;

						case 'who':
							$this->output_split(@$post['who_2'], $class . '-who');
							break;
					}
				}
				$this->output('</div>');
			$this->output('</div>');
		}

		$this->output('</span>');
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

		$this->output('<div onclick="toggleExtra(this)" class="qam-q-extra-menu">');
			if (isset($q_view['main_form_tags'])) {
				$this->output('<form ' . $q_view['main_form_tags'] . '>'); // form for buttons on question
				$this->output('<div class="qam-q-extra-menu-toggle"><i class="material-icons">more_vert</i></div>');
			}
			$this->q_view_buttons($q_view);
			if (isset($q_view['main_form_tags'])) {
				$this->form_hidden_elements(@$q_view['buttons_form_hidden']);
				$this->output('</form>');
			}
		$this->output('</div>');

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
			if (isset($q_view['main_form_tags'])) {
				$this->output('<form ' . $q_view['main_form_tags'] . ' class="qam-rest-buttons">'); // form for buttons on question
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

		$this->output('<div onclick="toggleExtra(this)" class="qam-q-extra-menu">');
			if (isset($c_item['main_form_tags'])) {
				$this->output('<form ' . $c_item['main_form_tags'] . '>'); // form for buttons on comment
				$this->output('<div class="qam-q-extra-menu-toggle"><i class="material-icons">more_vert</i></div>');
			}
			$this->c_item_buttons($c_item);
			if (isset($c_item['main_form_tags'])) {
				$this->form_hidden_elements(@$c_item['buttons_form_hidden']);
				$this->output('</form>');
			}
		$this->output('</div>');
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
				$this->output('<form ' . $c_item['main_form_tags'] . ' class="qam-rest-buttons">'); // form for buttons on comment
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

		$this->output('<div onclick="toggleExtra(this)" class="qam-q-extra-menu">');
			if (isset($a_item['main_form_tags'])) {
				$this->output('<form ' . $a_item['main_form_tags'] . '>'); // form for buttons on answer
				$this->output('<div class="qam-q-extra-menu-toggle"><i class="material-icons">more_vert</i></div>');
			}
			$this->a_item_buttons($a_item);
			if (isset($a_item['main_form_tags'])) {
				$this->form_hidden_elements(@$a_item['buttons_form_hidden']);
				$this->output('</form>');
			}
		$this->output('</div>');
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
				$this->output('<form ' . $a_item['main_form_tags'] . ' class="qam-rest-buttons">'); // form for buttons on answer
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
		elseif (isset($post['unselect_tags']))
			$this->post_hover_button($post, 'unselect_tags', '', 'qa-a-unselect');
		elseif ($post['selected'])
			$this->output('<div class="qa-a-selected">&nbsp;</div>');
		if (isset($post['select_text'])){
			$this->output('<i class="material-icons selected">verified</i>');
			$this->output('<div class="qa-a-selected-text">' . @$post['select_text'] . '</div>');
		}
		$this->output('</div>');
	}

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
		$this->vote_count($post);
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
				$this->output('<i class="material-icons">thumb_down_off_alt</i>');
				$this->output_split($post['downvotes_view'], 'disabled qa-downvote-count');
				break;

			case 'voted_down':
				$this->post_disabled_button($post, 'vote_up_tags', '', 'qa-vote-first-button qa-vote-up');
				$this->output('<i class="material-icons">thumb_up_off_alt</i>');
				$this->output_split($post['upvotes_view'], 'disabled qa-upvote-count');

				$this->post_hover_button($post, 'vote_down_tags', '&ndash;', 'qa-vote-one-button qa-voted-down');
				$this->output('<i class="material-icons voted_down">thumb_down_alt</i>');
				$this->output_split($post['downvotes_view'], 'selected qa-downvote-count');
				break;

			case 'up_only':
				$this->post_hover_button($post, 'vote_up_tags', '+', 'qa-vote-first-button qa-vote-up');
				$this->output('<i class="material-icons up_only">thumb_up_off_alt</i>');
				$this->output_split($post['upvotes_view'], 'enabled qa-upvote-count');
				break;

			case 'enabled':
				$this->post_hover_button($post, 'vote_up_tags', '+', 'qa-vote-first-button qa-vote-up');
				$this->output('<i class="material-icons enabled">thumb_up_off_alt</i>');
				$this->output_split($post['upvotes_view'], 'enabled qa-upvote-count');

				$this->post_hover_button($post, 'vote_down_tags', '&ndash;', 'qa-vote-second-button qa-vote-down');
				$this->output('<i class="material-icons enabled">thumb_down_off_alt</i>');
				$this->output_split($post['downvotes_view'], 'enabled qa-downvote-count');
				break;

			default:
				$this->post_disabled_button($post, 'vote_up_tags', '', 'qa-vote-first-button qa-vote-up');
				$this->output('<i class="material-icons">thumb_up_off_alt</i>');
				$this->output_split($post['upvotes_view'], 'disabled qa-upvote-count');

				$this->post_disabled_button($post, 'vote_down_tags', '', 'qa-vote-second-button qa-vote-down');
				$this->output('<i class="material-icons">thumb_down_off_alt</i>');
				$this->output_split($post['downvotes_view'], 'disabled qa-downvote-count');
				break;
		}

		$this->output('</div>');
	}

	public function vote_count($post)
	{
		if(isset($post['vote_count_tags']))
			$this->output('<i class="material-icons md-18 qam-vote-info"' . $post['vote_count_tags'] . '>info_outlined</i>');
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
			'<a href="https://github.com/MominRaza/MayroPro">MayroPro Theme</a> by <a href="https://mominraza.github.io">Momin Raza</a>',
			'</div>'
		);
		parent::attribution();
	}
}
?>
