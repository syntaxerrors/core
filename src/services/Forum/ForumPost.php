<?php namespace Syntax\Core\Forum;

class ForumPost {

	public $pageCount;

	public $currentPage = 1;

	public $post;

	public $type;

	public $parts;

	public $replyTypes;

	public $statuses;

	public $repliesPerPage = '30';

	public function get()
	{
		return $this;
	}

	public function make()
	{
		\CoreView::addData('post', $this->post)->addData('details', $this);
	}

	public function setUp($post, $page)
	{
		$this->post        = $post;
		$this->currentPage = $page != null ? $page : 1;

		$this->post->incrementViews();
		$this->post->userViewed(\CoreView::getActiveUser()->id);

		$this->setType($this->post->type->keyName)->setParts()->setDefaultData();

		return $this;
	}

	public function setPost($post)
	{
		$this->post = $post;

		return $this;
	}

	public function setType($type)
	{
		$this->type = $type;

		$this->setParts();

		return $this;
	}

	public function setRepliesPerPage($count)
	{
		$this->repliesPerPage = $count;

		return $this;
	}

	public function setDefaultData()
	{
		if ($this->type == 'IMAGE') {
			$replyTypes= \Forum_Reply_Type::where('keyName', 'IMAGE')->get();
			$this->replyTypes = \CoreView::arrayToSelect($replyTypes , 'id', 'name', false);
		} else {
			$replyTypes       = \Forum_Reply_Type::orderByNameAsc()->remember(60)->get();
			$this->replyTypes = \CoreView::arrayToSelect($replyTypes , 'id', 'name', 'Select Reply Type');
		}

		$statuses       = \Forum_Support_Status::remember(60)->get();
		$this->statuses = \CoreView::arrayToSelect($statuses, 'id', 'name', 'Select a Status');

		return $this;
	}

	public function setParts()
	{
		$this->parts = new \stdClass();

		$this->parts->sidebar = 'forum.post.components.sidebar.'. \Str::lower($this->type);

		if (\CoreView::checkView($this->parts->sidebar) == false) {
			$this->parts->sidebar = 'forum.post.components.sidebar.default';
		}

		if ($this->currentPage == 1) {
			$this->parts->post = $this->post;
		} else {
			$this->parts->post = null;
		}

		if ($this->post->replies->count() > $this->repliesPerPage) {
			$this->pageCount = \ceil($this->post->replies->count() / $this->repliesPerPage);
		}

		if ($this->currentPage > 1) {
			$skip = ($this->currentPage - 1) * $this->repliesPerPage;
		} else {
			$skip = 0;
		}

		$this->parts->replies = \Forum_Reply::with('author')->where('forum_post_id', $this->post->id)->orderBy('created_at', 'asc')->skip($skip)->take($this->repliesPerPage)->get();

		return $this;
	}

	public function addImage($imageDirectory, $image)
	{
		$verify = \CoreImage::verifyImage($image);
		if ($verify != true) {
			return $this;
		}

		$directory = public_path() .'/img/forum/'. $imageDirectory .'/images/'. $this->post->id;

		\CoreImage::addImage($directory, $image, null, true);

		return $this;
	}

	public function verifyImage($image)
	{
		return \CoreImage::verifyImage($image);
	}
}