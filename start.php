<?php
 
elgg_register_event_handler('init', 'system', 'c_notification_messages');

function c_notification_messages() {

	elgg_register_library('c_notify_msg', elgg_get_plugins_path().'c_notification_messages/lib/functions.php');
	elgg_load_library('c_notify_msg');
	$action_path = elgg_get_plugins_path().'c_notification_messages/actions';

	if (elgg_get_plugin_setting("notifications","html_email_handler") == "yes") {
		set_view_location("html_email_handler/notification/body", elgg_get_plugins_path().'c_notification_messages/views/'); 
	}
	
	// personal & groups notifications 
	elgg_unregister_action('comments/add');
	elgg_register_action('comments/add',"$action_path/comments/add.php");

	elgg_unregister_action('likes/add');
	elgg_register_action('likes/add',"$action_path/likes/add.php");

	elgg_unregister_action('groups/addtogroup');
	elgg_register_action('groups/addtogroup',"$action_path/groups/membership/add.php");

	elgg_unregister_action('groups/invite');
	elgg_register_action('groups/invite',"$action_path/groups/membership/invite.php");

	elgg_unregister_action('photos/image/upload');
	elgg_unregister_action('photos/image/ajax_upload_complete');
	elgg_register_action('photos/image/ajax_upload_complete',"$action_path/photos/image/ajax_upload_complete.php",'logged_in');
	elgg_register_action('photos/image/upload',"$action_path/photos/image/upload.php");

	elgg_unregister_action('photos/album/save');
	elgg_register_action('photos/album/save',"$action_path/photos/album/save.php");

	elgg_unregister_plugin_hook_handler('notify:entity:message', 'object', 'tidypics_notify_message');
    elgg_register_plugin_hook_handler('notify:entity:message', 'object', 'c_tidypics_notify_message');

	elgg_unregister_action('blog/save');
	elgg_register_action('blog/save',"$action_path/blog/save.php");
	elgg_unregister_plugin_hook_handler('notify:entity:message','object','blog_notify_message');
	elgg_register_plugin_hook_handler('notify:entity:message','object','c_blog_notify_message');
	
	elgg_unregister_action('group_tools/mail');
	elgg_register_action('group_tools/mail',"$action_path/group_tools/mail.php");

	// elgg_unregister_plugin_hook_handler('cron','fiveminute','event_calendar_handle_reminders_cron');
	// elgg_register_plugin_hook_handler('cron','fiveminute','c_event_calendar_handle_reminders_cron',400);
 
	register_notification_object('object','bookmarks',elgg_echo('bookmarks:new'));
	elgg_unregister_plugin_hook_handler('notify:entity:message','object','bookmarks_notify_message');
	elgg_register_plugin_hook_handler('notify:entity:message','object','c_bookmarks_notify_message');

	register_notification_object('object','event_calendar',elgg_echo('event_calendar:new_event'));
	elgg_register_plugin_hook_handler('notify:entity:message','object','c_event_notify_message');

	elgg_unregister_plugin_hook_handler("notify:annotation:subject", "group_topic_post", "advanced_notifications_discussion_reply_subject_hook");
	elgg_register_plugin_hook_handler("notify:annotation:subject", "group_topic_post", "c_advanced_notifications_discussion_reply_subject_hook");
	elgg_unregister_plugin_hook_handler('notify:annotation:message','group_topic_post','discussion_create_reply_discussion');
	elgg_register_plugin_hook_handler('notify:annotation:message','group_topic_post','c_discussion_create_reply_notification');
	
	register_notification_object('object', 'groupforumtopic', elgg_echo('discussion:notification:topic:subject'));
	
	elgg_unregister_plugin_hook_handler('notify:entity:message', 'object', 'groupforumtopic_notify_message');
	elgg_register_plugin_hook_handler('notify:entity:message','object','c_groupforumtopic_notify_message');

	elgg_unregister_plugin_hook_handler('notify:entity:message', 'object', 'file_notify_message');
	elgg_register_plugin_hook_handler('notify:entity:message', 'object', 'c_file_notify_message');
	
	elgg_unregister_plugin_hook_handler('notify:entity:message','object','page_notify_message');
	elgg_register_plugin_hook_handler('notify:entity:message','object','c_page_notify_message');

	elgg_unregister_plugin_hook_handler('notify:entity:message','object','task_notify_message');
	elgg_register_plugin_hook_handler('notify:entity:message','object','c_task_notify_message');
	
	
	// cyu - 04/28/2015: check if friend_request is installed and activated
	if (elgg_is_active_plugin('friend_request')) {
		// cyu - 04/28/2015: if friend_request is installed, unregister the function and put mine in
		elgg_unregister_event_handler("create", "friendrequest", "friend_request_event_create_friendrequest");
		elgg_register_event_handler("create", "friendrequest", "c_friend_request_event_create_friendrequest");
	} else {
		// cyu - 04/28/2015: else unregister core function and put mine in
		elgg_unregister_event_handler('create','friend','relationship_notification_hook');
		elgg_register_event_handler('create','friend','c_relationship_notification_hook'); 
	}
	
	elgg_unregister_plugin_hook_handler('notify:entity:message', 'object', 'thewire_notify_message');
	elgg_register_plugin_hook_handler('notify:entity:message', 'object', 'c_thewire_notify_message');
	
	elgg_unregister_action('messages/send');
	elgg_register_action("messages/send","$action_path/messages/send.php");
	 
	// system wide notifications
	elgg_unregister_action('useradd');
	elgg_register_action('useradd',"$action_path/user/useradd.php",'admin');

	// disables a user upon registration
	elgg_unregister_plugin_hook_handler('register','user','uservalidationbyemail_disable_new_user');
	elgg_register_plugin_hook_handler('register','user','c_uservalidationbyemail_disable_new_user');

	elgg_unregister_action('user/requestnewpassword');
	elgg_register_action('user/requestnewpassword',"$action_path/user/requestnewpassword.php",'public');
 
	elgg_unregister_action('user/passwordreset');
	elgg_register_action('user/passwordreset',"$action_path/user/passwordreset.php",'public');

	
	// other notifications
	if (elgg_action_exists('invitefriends/invite'))
	{
		elgg_unregister_action('invitefriends/invite');
		elgg_register_action('invitefriends/invite',"$action_path/invitefriends/invite.php");
	}

	elgg_unregister_action('event_calendar/request_personal_calendar');
	elgg_register_action("event_calendar/request_personal_calendar","$action_path/event_calendar/request_personal_calendar.php");
	
	if (elgg_action_exists('photos/image/tag'))
	{
		elgg_unregister_action('photos/image/tag');
		elgg_register_action('photos/image/tag',"$action_path/photos/image/tag.php");
	} 
	
	elgg_unregister_action("messageboard/add");
	elgg_register_action("messageboard/add", "$action_path/messageboard/add.php");
	
	//elgg_unregister_action("groups/invite");
	//elgg_register_action("groups/invite", "$action_path/group_tools/invite.php");
	
	elgg_unregister_event_handler("create", "membership_request", "group_tools_membership_request");
	elgg_register_event_handler("create", "membership_request", "c_group_tools_membership_request");

	elgg_unregister_action("groups/join");
	elgg_register_action("groups/join", "$action_path/groups/membership/join.php");
	
	elgg_unregister_action('group_tools/admin_transfer');
	elgg_register_action("group_tools/admin_transfer", "$action_path/group_tools/admin_transfer.php");

	// cyu - 01/29/2015: need to overwrite the pam_handler so that users who forgot to validate will receive proper email
 	unregister_pam_handler('uservalidationbyemail_check_auth_attempt');
	register_pam_handler('c_uservalidationbyemail_check_auth_attempt', "required");

}

// function c_event_calendar_handle_reminders_cron() {
// 	elgg_load_library('c_notify_msg');
// 	c_event_calendar_queue_reminders();
// }