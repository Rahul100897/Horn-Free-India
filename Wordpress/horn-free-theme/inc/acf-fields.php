<?php
/** ACF field definitions and safe fallback helpers. */

function horn_free_theme_field( $name, $default = '', $post_id = false ) {
	if ( function_exists( 'get_field' ) ) {
		$value = get_field( $name, $post_id ?: get_the_ID() );
		if ( false !== $value && null !== $value && '' !== $value ) {
			return $value;
		}
	}
	return $default;
}

function horn_free_theme_acf_fields() {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) { return; }
	// Allow an imported/database-managed copy of this group to take precedence.
	if ( function_exists( 'acf_get_field_group' ) && acf_get_field_group( 'group_hfi_homepage' ) ) { return; }
	$fields = array();
	$tabs = array(
		'hero' => 'Hero', 'problem' => 'Problem', 'action' => 'Take Action', 'ask' => 'Our Ask',
		'proof' => 'Proof', 'momentum' => 'Momentum', 'story' => 'Our Story', 'closing' => 'Closing CTA',
	);
	$definitions = array(
		'hero' => array( 'hero_eyebrow'=>'Eyebrow|text', 'hero_title'=>'Title|text', 'hero_text'=>'Introduction|textarea', 'hero_image'=>'Image|image', 'hero_primary_label'=>'Primary button label|text', 'hero_secondary_label'=>'Secondary button label|text', 'hero_trust'=>'Trust line|text' ),
		'problem' => array( 'problem_title'=>'Title|text', 'problem_intro'=>'Introduction|textarea', 'stat_1_number'=>'Stat 1 number|text', 'stat_1_text'=>'Stat 1 text|textarea', 'stat_2_number'=>'Stat 2 number|text', 'stat_2_text'=>'Stat 2 text|textarea', 'stat_3_number'=>'Stat 3 number|text', 'stat_3_text'=>'Stat 3 text|textarea' ),
		'ask' => array( 'ask_title'=>'Title|text', 'ask_intro'=>'Introduction|textarea', 'ask_card_1'=>'Card 1|text', 'ask_card_2'=>'Card 2|text', 'ask_card_3'=>'Card 3|text', 'ask_card_3_note'=>'Card 3 note|text', 'ask_closing'=>'Closing line|text' ),
		'proof' => array( 'proof_title'=>'Title|text', 'proof_text'=>'Body|textarea', 'proof_punchline'=>'Punchline|textarea' ),
		'action' => array( 'action_title'=>'Title|text', 'action_intro'=>'Introduction|text', 'action_step_1_title'=>'Step 1 title|text', 'action_step_1_text'=>'Step 1 text|textarea', 'action_step_2_title'=>'Step 2 title|text', 'action_step_2_text'=>'Step 2 text|textarea', 'action_step_3_title'=>'Step 3 title|text', 'action_step_3_text'=>'Step 3 text|textarea', 'action_closing'=>'Closing line|text' ),
		'momentum' => array( 'momentum_title'=>'Title|text', 'movement_starting_count'=>'Verified existing count|number', 'momentum_label'=>'Counter label|text', 'voice_quote_1'=>'Voice quote 1|textarea', 'voice_author_1'=>'Voice author 1|text', 'voice_quote_2'=>'Voice quote 2|textarea', 'voice_author_2'=>'Voice author 2|text', 'voice_quote_3'=>'Voice quote 3|textarea', 'voice_author_3'=>'Voice author 3|text' ),
		'story' => array( 'story_title'=>'Title|text', 'story_text'=>'Story|textarea', 'promise_1'=>'Promise 1|textarea', 'promise_2'=>'Promise 2|textarea', 'promise_3'=>'Promise 3|textarea' ),
		'closing' => array( 'closing_title'=>'Title|text', 'closing_text'=>'Body|textarea', 'closing_button'=>'Button label|text' ),
	);
	$defaults = array(
		'hero_eyebrow'=>'A citizen movement for a quieter India',
		'hero_title'=>'From <span class="loud">Blow Horn</span> to <span class="calm">Om Shanti</span>',
		'hero_text'=>'India gave the world the word for peace — शांति. Yet every truck on our roads still orders us to Blow Horn. We’re here to change what our roads say, and how they sound.',
		'hero_primary_label'=>'Join the movement', 'hero_secondary_label'=>'See why it matters',
		'hero_trust'=>'No donations. No politics. We will never ask you for money — only for your voice.',
		'problem_title'=>'Our roads are loud, and it’s costing us more than our calm.',
		'problem_intro'=>'Constant honking isn’t just noise. It’s a daily assault on our hearing, our nerves, and our cities — and it’s so normal we’ve stopped noticing.',
		'stat_1_number'=>'80–120 dB', 'stat_1_text'=>'A single truck horn. The safe limit for a residential street is 55 dB.',
		'stat_2_number'=>'1.5 lakh+ deaths', 'stat_2_text'=>'Lives lost on Indian roads every year. Roads this dangerous shouldn’t also be this hostile.',
		'stat_3_number'=>'Stress, BP, Tinnitus', 'stat_3_text'=>'Horns lead to higher stress levels, higher blood pressure, and ear damage called tinnitus.',
		'ask_title'=>'We’re not asking for silence. We’re asking for three words to change.',
		'ask_intro'=>'For decades, India’s commercial vehicles have carried signage that invites honking. Our ask is simple: replace that signage with a message that asks for the opposite.',
		'ask_card_1'=>'Stop Horn', 'ask_card_2'=>'No Horn', 'ask_card_3'=>'Om Shanti', 'ask_card_3_note'=>'peace, for the road',
		'ask_closing'=>'One change, on the back of every truck. From a command to honk — to a reminder to breathe.',
		'proof_title'=>'This isn’t a dream. It’s already been done once.',
		'proof_text'=>'In 2015, Maharashtra’s Transport Commissioner ordered “Horn OK Please” removed from commercial vehicles across the state — with a single administrative circular.',
		'proof_punchline'=>'If one state can do it with the stroke of a pen, the country can too. We just have to ask — together.',
		'action_title'=>'Here’s the whole movement, in three minutes.', 'action_intro'=>'No marches. No money. Three steps from your phone.',
		'action_step_1_title'=>'Add your name', 'action_step_1_text'=>'Tell us who you are and where you’re from. Your name joins a public count we put in front of the government.',
		'action_step_2_title'=>'Email the Ministry', 'action_step_2_text'=>'One tap fills in a respectful message to the Ministry of Road Transport & Highways.',
		'action_step_3_title'=>'Bring three friends', 'action_step_3_text'=>'Forward this to three people who would want a quieter India.',
		'action_closing'=>'That’s all. No fee, no form-filling marathon, no catch. Just your voice, counted.',
		'momentum_title'=>'You’d be joining a movement that’s already moving.', 'movement_starting_count'=>0,
		'momentum_label'=>'People have added their voices',
		'voice_quote_1'=>'I forgot what quiet sounds like. I want it back.', 'voice_author_1'=>'— A supporter',
		'voice_quote_2'=>'My daughter covers her ears every morning on the way to school.', 'voice_author_2'=>'— A parent',
		'voice_quote_3'=>'I drive a truck. I never wanted my work to be the loudest thing on the street.', 'voice_author_3'=>'— A driver',
		'story_title'=>'Who’s behind this?', 'story_text'=>'Horn Free India is a citizen movement. We are not an NGO, not a company, and not tied to any political party.',
		'promise_1'=>'We never take money. The only thing we collect is voices.', 'promise_2'=>'We’re non-partisan. This is about peace and safety, not politics.', 'promise_3'=>'We’re real and reachable.',
		'closing_title'=>'India said <span class="calm">Om Shanti</span> to the world. It’s time our streets said it too.',
		'closing_text'=>'It costs you nothing but a name and three minutes.', 'closing_button'=>'Join the movement',
	);
	foreach ( $tabs as $key => $label ) {
		$fields[] = array( 'key'=>'field_hfi_tab_'.$key, 'label'=>$label, 'name'=>'', 'type'=>'tab' );
		foreach ( $definitions[$key] as $name => $meta ) {
			list( $field_label, $type ) = explode( '|', $meta );
			$field = array( 'key'=>'field_hfi_'.$name, 'label'=>$field_label, 'name'=>$name, 'type'=>$type, 'default_value'=>$defaults[$name] ?? '' );
			if ( 'image' === $type ) { $field['return_format'] = 'array'; $field['preview_size'] = 'medium'; $field['instructions'] = 'Optional. If empty, the bundled homepage image is displayed automatically.'; }
			$fields[] = $field;
		}
	}
	acf_add_local_field_group( array(
		'key'=>'group_hfi_homepage', 'title'=>'Horn Free India — Homepage', 'fields'=>$fields,
		'location'=>array( array( array( 'param'=>'page_type', 'operator'=>'==', 'value'=>'front_page' ) ) ),
		'menu_order'=>0, 'position'=>'normal', 'style'=>'default', 'active'=>true,
	) );
}
add_action( 'acf/init', 'horn_free_theme_acf_fields' );

function horn_free_theme_acf_notice() {
	if ( current_user_can( 'activate_plugins' ) && ! function_exists( 'get_field' ) ) {
		echo '<div class="notice notice-warning"><p><strong>Horn Free India:</strong> Install and activate Advanced Custom Fields to edit the dynamic homepage content.</p></div>';
	}
}
add_action( 'admin_notices', 'horn_free_theme_acf_notice' );
