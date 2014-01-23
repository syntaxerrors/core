<?php

class Core_Forum_PostController extends BaseController {

    public $type = 'forum';

    public function getView($postId, $page = 1)
    {
        $post = Forum_Post::with('author', 'status')->find($postId);

        ForumPost::setUp($post, $page)->make();
    }

    public function postView($postId)
    {
        // Handle form input
        $input = e_array(Input::all());

        if ($input != null) {
            $post = Forum_Post::find($postId);

            // Handle moderations
            if (isset($input['report_resource_id']) && $input['report_resource_id'] != null) {
                $this->submitModeration($input['report_resource_name'], $input['report_resource_id'], $input['reason']);

                return $this->redirect('forum/post/view/'. $postId, 'Your report has been submitted to our moderators.');
            }

            // Handle replies
            if (isset($input['content']) && $input['content'] != null) {
                $reply = $this->submitReply($input, $post);

                return $this->redirect('forum/post/view/'. $postId .'#reply:'. $reply->id);
            }
        }
    }

    protected function submitReply($input, $post)
    {
        $message = e($input['content']);

        if (Input::hasFile('image')) {
            $verify = ForumPost::verifyImage(Input::file('image'));

            if ($verify == false) {
                $this->addError('failedUpload', 'The file you submitted is not an image.');
                return $this->redirect();
            }
        }

        // We are adding a reply
        $reply                      = new Forum_Reply;
        $reply->forum_post_id       = $post->id;
        $reply->forum_reply_type_id = ($input['forum_reply_type_id'] == 9999 ? Forum_Reply::TYPE_ACTION : $input['forum_reply_type_id']);
        $reply->user_id             = $this->activeUser->id;
        $reply->name                = (isset($input['name']) && $input['name'] != null ? $input['name'] : 'Re: '. $post->name);
        $reply->keyName             = Str::slug($reply->name);
        $reply->content             = $message;
        $reply->quote_id            = (isset($input['quote_id']) && strlen($input['quote_id']) == 10 ? $input['quote_id'] : null);
        $reply->moderatorLockedFlag = 0;
        $reply->adminReviewFlag     = 0;
        $reply->approvedFlag        = ($input['forum_reply_type_id'] == 9999 ? 1 : 0);

        $this->save($reply);

        $reply->post->modified_at = date('Y-m-d H:i:s');
        $this->checkErrorsSave($reply->post);

        if (Input::hasFile('image')) {
            ForumPost::setPost($reply)->addImage('replies', Input::file('image'));
        }

        // Remove all user views so the post shows as updated
        $post->deleteViews();

        // See if we are updating the status
        if (isset($input['forum_support_status_id']) && $input['forum_support_status_id'] != 0) {
            $status                          = Forum_Post_Status::where('forum_post_id', $post->id)->first();
            $status->forum_support_status_id = $input['forum_support_status_id'];
            $this->save($status);
        }

        return $reply;
    }

    protected function submitImage($post, $imageName)
    {
        // Upload the image
        Input::upload('image', public_path() .'/img/forum/attachments/'. $postId, $imageName);

        // Add the upload to edit history
        $post->setAttachmentEdit($imageName);
    }

    protected function submitModeration($type, $resourceId, $reason)
    {
        // Get the correct resource
        if ($type == 'post') {
            $resource = Forum_Post::find($resourceId);
        } else {
            $resource = Forum_Reply::find($resourceId);
        }

        // Create the moderator record and lock the resource
        $resource->setModeration($reason);
    }

    public function getEdit($type, $resourceId)
    {
        // Make sure they can access this whole area
        $this->checkPermission('FORUM_POST');

        // Get the information
        $resourceClass  = ($type == 'post' ? 'Forum_Post' : 'Forum_Reply');
        $resource       = $resourceClass::find($resourceId);

        // Verify the user
        if (!$this->activeUser->checkPermission(array('DEVELOPER', 'FORUM_MOD', 'FORUM_ADMIN'))) {
            if ($resource->user_id != $this->activeUser->id) {
                $url = ($type == 'post' ? 'forum/post/'. $resourceId : 'forum/post/'. $resource->post->id);
                $this->redirect($url, 'You must be a moderator or the post author to edit a post.');
            }
        }

        // Get the available post types
        $types = $this->arrayToSelect($resource->getForumTypes(), 'id', 'name', 'Select Post Type');

        // Set the template
        $this->setViewData('types', $types);
        $this->setViewData('post', $resource);
    }

    public function postEdit($type, $resourceId)
    {
        // Handle any form data
        $input = e_array(Input::all());

        if ($input != null) {
            // Get the information
            switch ($type) {
                case 'post':
                    $post                     = Forum_Post::find($resourceId);
                    $post->forum_post_type_id = (isset($input['forum_post_type_id']) && $input['forum_post_type_id'] != 0 ? $input['forum_post_type_id'] : null);
                    $post->name               = $input['name'];
                    $post->keyName            = Str::slug($input['name']);
                    $post->content            = e($input['content']);

                    $this->checkErrorsSave($post);

                    // Add the edit history
                    $reason = (isset($input['reason']) && $input['reason'] != null ? $input['reason'] : null);
                    $post->addEdit($reason);

                    return $this->redirect('forum/post/view/'. $post->uniqueId, $post->name.' has been updated.');

                break;
                case 'reply':
                    $reply                      = Forum_Reply::find($resourceId);
                    $reply->forum_reply_type_id = (isset($input['forum_reply_type_id']) && $input['forum_reply_type_id'] != 0 ? $input['forum_reply_type_id'] : $reply->forum_reply_type_id);
                    $reply->name                = $input['name'];
                    $reply->keyName             = Str::slug($input['name']);
                    $reply->content             = e($input['content']);

                    $this->checkErrorsSave($reply);

                    // Add the edit history
                    $reason = (isset($input['reason']) && $input['reason'] != null ? $input['reason'] : null);
                    $reply->addEdit($reason);

                    return $this->redirect('forum/post/view/'. $reply->post->uniqueId .'#reply:'. $reply->id, $reply->name.' has been updated.');

                break;
            }
        }
    }

    public function getModify($id, $property, $value, $type = 'post')
    {
        $this->skipView();

        switch ($type) {
            case 'post':
                $resource = Forum_Post::find($id);
            break;
            case 'reply':
                $resource = Forum_Reply::find($id);
            break;
        }
        $resource->{$property} = $value;
        $this->save($resource);

        // Send mail if approving
        if ($resource->type->keyName == 'action' && $property == 'approvedFlag') {
            $message                  = new Message;
            $message->message_type_id = Message::ACTION_APPROVAL;
            $message->sender_id       = $this->activeUser->id;
            $message->receiver_id     = $resource->user_id;
            $message->title           = 'Your action post has been approved!';
            $message->content         = 'Your action post has been approved.<br /><br />Click '. HTML::link('forum/post/view/'. $resource->post->uniqueId .'#reply:'. $resource->id, 'here') .' to view your post.';
            $message->readFlag        = 0;

            $this->save($message);
        }

        return $this->redirect('back', $resource->name.' has been updated.');
    }

    public function getAdd($boardId = null)
    {
        // Make sure they can access this
        $this->checkPermission('FORUM_POST');

        // Get the information
        $board      = Forum_Board::where('uniqueId', $boardId)->first();
        $types      = $this->arrayToSelect(Forum_Post_Type::orderByNameAsc()->get(), 'id', 'name', 'Select Post Type');

        // Set the template
        $this->setViewData('types', $types);
        $this->setViewData('board', $board);
    }

    public function postAdd($boardId)
    {
        // Handle any form data
        $input = e_array(Input::all());

        if (Input::hasFile('image')) {
            $verify = ForumPost::verifyImage(Input::file('image'));

            if ($verify == false) {
                $this->addError('failedUpload', 'The file you submitted is not an image.');
                return $this->redirect();
            }
        }

        if ($input != null) {
            $board   = Forum_Board::where('uniqueId', $boardId)->first();
            $message = e($input['content']);

            if (count(Input::file('images')) > 0) {
                $message .= "\n". count(Input::file('images')) .' images attached.';
            }

            $post                      = new Forum_Post;
            $post->forum_board_id      = $board->id;
            $post->forum_post_type_id  = (isset($input['forum_post_type_id']) && $input['forum_post_type_id'] != 0 ? $input['forum_post_type_id'] : null);
            $post->user_id             = $this->activeUser->id;
            $post->name                = $input['name'];
            $post->keyName             = Str::slug($input['name']);
            $post->content             = $message;
            $post->moderatorLockedFlag = 0;
            $post->approvedFlag        = 0;
            $post->modified_at         = date('Y-m-d H:i:s');

            $this->checkErrorsSave($post);

            if (Input::hasFile('image') && $verify == true) {
                ForumPost::setPost($post)->addImage('posts', Input::file('image'));
            }

            // Set status if a support post
            if ($post->board->category->forum_category_type_id == Forum_Category::TYPE_SUPPORT) {
                $post->setStatus(Forum_Support_Status::TYPE_OPEN);
            }

            // Set this user as already having viewed the post
            $post->userViewed($this->activeUser->id);

            return $this->redirect('forum/post/view/'. $post->id, $post->name.' has been submitted.');
        }
    }

    public function postUpdate($resourceId, $property, $value, $type = 'post')
    {
        $this->skipView();

        switch ($type) {
            case 'post':
                $resource = Forum_Post::find($resourceId);
            break;
            case 'status':
                $resource = Forum_Post_Status::where('forum_post_id', $resourceId)->first();
                $property = 'forum_support_status_id';
            break;
        }
        $resource->{$property} = $value;

        $this->save($resource);
    }


    public function getDelete($resourceId, $type = 'post', $attachment = null)
    {
        // Don't load a page
        $this->skipView();

        // Make sure they can access this
        $this->checkPermission('FORUM_POST');

        if ($type == 'attachment') {
            $attachment = str_replace('%7C', '/', $attachment);
            File::delete($attachment);

            return $this->redirect('forum/post/view/'. $resourceId, 'Attachment deleted.');

        } elseif ($type == 'post') {
            $post    = Forum_Post::find($resourceId);

            // Verify the user
            if (!$this->activeUser->checkPermission(array('DEVELOPER', 'FORUM_MOD', 'FORUM_ADMIN'))) {
                if ($post->user_id != $this->activeUser->id) {
                    $this->authFailed('forum/post/'. $resourceId, 'You must be a moderator or the post author to delete a post.');
                }
            }

            // Delete the post
            $post->delete();

            return $this->redirect('forum/board/view/'. $post->board->id, 'Post '. $post->name.' has been deleted.');
        } else {
            $reply = Forum_Reply::find($resourceId);

            // Verify the user
            if (!$this->activeUser->checkPermission(array('DEVELOPER', 'FORUM_MOD', 'FORUM_ADMIN'))) {
                if ($reply->user_id != $this->activeUser->id) {
                    $this->redirect('forum/post/view/'. $reply->post->id .'#reply:'. $reply->id, 'You must be a moderator or the post author to delete a post.');
                }
            }

            // Delete the reply
            $reply->delete();

            return $this->redirect('forum/post/view/'. $reply->post->id, 'Reply '. $reply->name.' has been deleted.');
        }
    }
}