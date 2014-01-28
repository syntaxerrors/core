<?php

class Core_Forum_BoardController extends BaseController {

    public function getView($boardId)
    {
        // Get the information
        $board              = Forum_Board::with('children')->find($boardId);

        $openIssues         = Forum_Post_Status::where('forum_support_status_id', Forum_Support_Status::TYPE_OPEN)->count();
        $inProgressIssues   = Forum_Post_Status::where('forum_support_status_id', Forum_Support_Status::TYPE_IN_PROGRESS)->count();
        $resolvedIssues     = Forum_Post_Status::where('forum_support_status_id', Forum_Support_Status::TYPE_RESOLVED)->count();
        $announcements      = Forum_Post::with('author')->where('forum_board_id', $board->id)->where('forum_post_type_id', Forum_Post::TYPE_ANNOUNCEMENT)->orderBy('modified_at', 'desc')->get();
        $posts              = Forum_Post::with('author')->where('forum_board_id', $board->id)->where('forum_post_type_id', '!=', Forum_Post::TYPE_ANNOUNCEMENT)->orderBy('modified_at', 'desc')->paginate(30);

        // Add quick links
        if ($this->hasPermission('FORUM_POST')) {
            // $this->addSubMenu('Add Post','forum/post/add/'. $boardSlug);
        }

        // Set the template
        $this->setViewData('announcements', $announcements);
        $this->setViewData('posts', $posts);
        $this->setViewData('board', $board);
        $this->setViewData('openIssues', $openIssues);
        $this->setViewData('inProgressIssues', $inProgressIssues);
        $this->setViewData('resolvedIssues', $resolvedIssues);
    }

    public function getAdd($categoryId = null)
    {
        // Make sure they can access this whole area
        $this->checkPermission('FORUM_ADMIN');

        // Get the information
        $category = null;
        if ($categoryId != null) {
            $category   = Forum_Category::find($categoryId);
        }
        $boards      = $this->arrayToSelect(Forum_Board::orderBy('name', 'asc')->get(), 'id', 'name', 'Select a parent board');
        $categories = $this->arrayToSelect(Forum_Category::orderBy('position', 'asc')->get(), 'id', 'name', 'Select Category');
        $types      = $this->arrayToSelect(Forum_Board_Type::orderBy('name', 'asc')->get(), 'id', 'name', 'Select Board Type');

        // Set the template
        $this->setViewData('boards', $boards);
        $this->setViewData('category', $category);
        $this->setViewData('categories', $categories);
        $this->setViewData('types', $types);

    }

    public function postAdd()
    {
        // Handle any form data
        $input = Input::all();

        if ($input != null) {
            $board                      = new Forum_Board;
            $board->name                = $input['name'];
            $board->forum_category_id   = (isset($input['forum_category_id']) && $input['forum_category_id'] != null ? $input['forum_category_id'] : null);
            $board->forum_board_type_id = (isset($input['forum_board_type_id']) && $input['forum_board_type_id'] != 0 ? $input['forum_board_type_id'] : null);
            $board->parent_id           = (isset($input['parent_id']) && strlen($input['parent_id']) == 10 ? $input['parent_id'] : null);
            $board->keyName             = Str::slug($input['name']);
            $board->description         = $input['description'];

            if ($board->parent_id != null) {
                $parent = Forum_Board::find($board->parent_id);
                $childrenCount = $parent->children->count();

                $board->position = $childrenCount + 1;
            } else {
                $category = Forum_Category::find($board->forum_category_id);
                $boardCount = $category->boards->count();

                $board->position = $boardCount + 1;
            }

            $this->checkErrorsSave($board);

            return $this->redirect(null, $board->name.' has been submitted.');
        }
    }
}