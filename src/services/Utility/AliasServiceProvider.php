<?php namespace Syntax\Core\Utility;

use Illuminate\Support\ServiceProvider;

class AliasServiceProvider extends ServiceProvider {

	/**
	 * Register the aliases.
	 *
	 * @return void
	 */
	public function register()
	{
		$aliases = [
			'HTML'                        => 'Syntax\Core\HTML',
			'View'                        => 'Syntax\Core\View\ViewFacade',
			'ForumPost'                   => 'Syntax\Core\Forum\Facades\ForumPost',
			'Menu'                        => 'Syntax\Core\Utility\Facades\Menu',
			'Mobile'                      => 'Syntax\Core\Utility\Facades\Mobile',
			'CoreView'                    => 'Syntax\Core\Utility\Facades\CoreView',
			'CoreImage'                   => 'Syntax\Core\Utility\Facades\CoreImage',
			'Crud'                        => 'Syntax\Core\Utility\Facades\Crud',
			'Wizard'                      => 'Syntax\Core\Utility\Facades\Wizard',
			'LeftTabs'                    => 'Syntax\Core\Utility\Facades\LeftTabs',
			'LeftTab'                     => 'Syntax\Core\Utility\Facades\LeftTab',
			'bForm'                       => 'Syntax\Core\Utility\Facades\bForm',
			'Ajax'                        => 'Syntax\Core\Utility\Facades\Ajax',
			'Post'                        => 'Syntax\Core\Utility\Facades\Post',
			'BBCode'                      => 'Syntax\Core\Utility\Facades\BBCode',
			'SocketIOClient'              => 'ElephantIO\Client',
			'Github'                      => 'Github\Client',
			'Chat'                        => 'Syntax\Core\Chat',
			'Forum'                       => 'Syntax\Core\Forum',
			'User'                        => 'Syntax\Core\User',
			'Message'                     => 'Syntax\Core\Message',
			'Chat_Room'                   => 'Syntax\Core\Chat_Room',
			'Forum_Board'                 => 'Syntax\Core\Forum_Board',
			'Forum_Board_Type'            => 'Syntax\Core\Forum_Board_Type',
			'Forum_Category'              => 'Syntax\Core\Forum_Category',
			'Forum_Category_Type'         => 'Syntax\Core\Forum_Category_Type',
			'Forum_Moderation'            => 'Syntax\Core\Forum_Moderation',
			'Forum_Moderation_Log'        => 'Syntax\Core\Forum_Moderation_Log',
			'Forum_Moderation_Reply'      => 'Syntax\Core\Forum_Moderation_Reply',
			'Forum_View'                  => 'Syntax\Core\Forum_View',
			'Forum_Post'                  => 'Syntax\Core\Forum_Post',
			'Forum_Post_Edit'             => 'Syntax\Core\Forum_Post_Edit',
			'Forum_Post_Status'           => 'Syntax\Core\Forum_Post_Status',
			'Forum_Post_Type'             => 'Syntax\Core\Forum_Post_Type',
			'Forum_Post_View'             => 'Syntax\Core\Forum_Post_View',
			'Forum_Reply'                 => 'Syntax\Core\Forum_Reply',
			'Forum_Reply_Edit'            => 'Syntax\Core\Forum_Reply_Edit',
			'Forum_Reply_Roll'            => 'Syntax\Core\Forum_Reply_Roll',
			'Forum_Reply_Type'            => 'Syntax\Core\Forum_Reply_Type',
			'Forum_Support_Status'        => 'Syntax\Core\Forum_Support_Status',
			'Message_Folder'              => 'Syntax\Core\Message_Folder',
			'Message_Folder_Message'      => 'Syntax\Core\Message_Folder_Message',
			'Message_Type'                => 'Syntax\Core\Message_Type',
			'Message_User_Delete'         => 'Syntax\Core\Message_User_Delete',
			'Message_User_Read'           => 'Syntax\Core\Message_User_Read',
			'User_Preference'             => 'Syntax\Core\User_Preference',
			'User_Preference_User'        => 'Syntax\Core\User_Preference_User',
			'User_Permission_Action'      => 'Syntax\Core\User_Permission_Action',
			'User_Permission_Action_Role' => 'Syntax\Core\User_Permission_Action_Role',
			'User_Permission_Role'        => 'Syntax\Core\User_Permission_Role',
			'User_Permission_Role_User'   => 'Syntax\Core\User_Permission_Role_User',
			'Seed'                        => 'Syntax\Core\Seed',
			'Migration'                   => 'Syntax\Core\Migration',
			'Control_Exception'           => 'Syntax\Core\Control_Exception',
		];

		$appAliases = \Config::get('core::nonCoreAliases');

		foreach ($aliases as $alias => $class) {
			if (!is_null($appAliases)) {
				if (!in_array($alias, $appAliases)) {
					\Illuminate\Foundation\AliasLoader::getInstance()->alias($alias, $class);
				}
			} else {
				\Illuminate\Foundation\AliasLoader::getInstance()->alias($alias, $class);
			}
		}
	}
}