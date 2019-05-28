<?php

class Comment
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    /**
     * @function addComment
     * @param array
     * @return boolean
     * Add post comment to comments table
     * with user id, post id , comment text
     */

    public function addComment($data)
    {
        $this->db->query('INSERT INTO comments (model_id, user_id, comment, model_name) VALUES (:model_id, :user_id, :comment, :model_name)');
        $this->db->bind(':model_id', $data['model_id']);
        $this->db->bind(':user_id', $data['user_id']);
        $this->db->bind(':comment', $data['comment']);
        $this->db->bind(':model_name', $data['model_name']);

        //execute
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @function getCommentsDetails
     * @param int (post id)
     * @return object array
     * Get comment by post id
     */
    public function getCommentsDetails($post_id)
    {
        $this->db->query('SELECT *,
            users.name as user_name,
            comments.created_At as commented_on,
            comments.id as comment_id            
            FROM comments
             INNER JOIN users
              ON comments.user_id = users.id AND comments.model_id = :post_id
             ORDER BY comments.created_At DESC ');
        $this->db->bind(':post_id', $post_id);

        $row = $this->db->resultSet();
        return $row;
    }

    /**
     * @function getCommentById
     * @param int (comment id)
     * @return object array
     */

    public function getCommentById($id)
    {
        $this->db->query('SELECT * FROM comments WHERE id =:id');
        $this->db->bind(':id', $id);

        $row = $this->db->single();
        return $row;
    }

    /**
     * @function deleteComment
     * @param int (comment id)
     * @return boolean true on success else false
     * Delete comment by using comment id
     */

    public function deleteComment($id, $model_name)
    {

        //$this->db->query('DELETE comments, comments_like_dislike FROM comments INNER JOIN comments_like_dislike ON comments.id =comments_like_dislike.comment_id  WHERE (comments.id=:id AND comments.model_name=:model_name) OR NOT IN (SELECT ld.comments_id FROM comments_like_dislike ld)');
        $this->db->query('DELETE FROM comments WHERE id = :id AND model_name=:model_name');
        $this->db->bind(':id', $id);
        $this->db->bind(':model_name', $model_name);

        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @function addNewLike
     * @param array
     * @return true on success else false
     * check like or dislike already in db if so then update the current status else insert new record.
     */
    public function addNewLike($data)
    {
        $this->db->query('SELECT * FROM comments_like_dislike WHERE comment_id = :comment_id AND user_id=:user_id AND (like_status = :like_status OR like_status=:like_status1)');
        $this->db->bind(':comment_id', $data['comment_id']);
        $this->db->bind(':user_id', $data['user_id']);
        $this->db->bind(':like_status', 1);
        $this->db->bind(':like_status1', 0);

        $row = $this->db->single();
        //var_dump($row); exit();
        if ($row) {
            $this->db->query('UPDATE comments_like_dislike SET like_status= :like_status WHERE id=:id');
            $this->db->bind(':like_status', $data['like_status']);
            $this->db->bind(':id', $row->id);

            //execute
            if ($this->db->execute()) {
                return true;
            } else {
                return false;
            }
        } else {
            $this->db->query('INSERT INTO comments_like_dislike (post_id,comment_id, user_id, like_status) VALUES (:post_id, :comment_id, :user_id, :like_status)');
            $this->db->bind(':post_id', $data['post_id']);
            $this->db->bind(':comment_id', $data['comment_id']);
            $this->db->bind(':user_id', $data['user_id']);
            $this->db->bind(':like_status', $data['like_status']);

            //execute
            if ($this->db->execute()) {
                return true;
            } else {
                return false;
            }
        }
    }

    /**
     * @function addNewdisLike
     * @param array
     * @return true on success else false
     * check like or dislike already in db if so then update the current status else insert new record.
     */
    public function addNewdisLike($data)
    {

        $this->db->query('SELECT * FROM comments_like_dislike WHERE comment_id = :comment_id AND user_id=:user_id AND (like_status = :like_status OR like_status=:like_status1)');
        $this->db->bind(':comment_id', $data['comment_id']);
        $this->db->bind(':user_id', $data['user_id']);
        $this->db->bind(':like_status', 1);
        $this->db->bind(':like_status1', 0);
        $row = $this->db->single();

        if ($row) {
            $this->db->query('UPDATE comments_like_dislike SET like_status= :like_status WHERE id=:id');
            $this->db->bind(':like_status', $data['like_status']);
            $this->db->bind(':id', $row->id);

            //execute
            if ($this->db->execute()) {
                return true;
            } else {
                return false;
            }
        } else {
            $this->db->query('INSERT INTO comments_like_dislike (post_id,comment_id, user_id, like_status) VALUES (:post_id, :comment_id, :user_id, :like_status)');
            $this->db->bind(':post_id', $data['post_id']);
            $this->db->bind(':comment_id', $data['comment_id']);
            $this->db->bind(':user_id', $data['user_id']);
            $this->db->bind(':like_status', $data['like_status']);

            //execute
            if ($this->db->execute()) {
                return true;
            } else {
                return false;
            }
        }
    }

    /**
     * @function getCommentLikesById
     * @param int (comment id)
     * @return count of total likes on that comment
     */
    public function getCommentLikesById($comment_id)
    {
        $this->db->query('SELECT COUNT(like_status) as likes FROM comments_like_dislike WHERE comment_id = :comment_id AND like_status = :like_status');
        $this->db->bind(':comment_id', $comment_id);
        $this->db->bind(':like_status', 1);
        return $this->db->single();
    }

    /**
     * @function getCommentDislikesById
     * @param int (comment id)
     * @return count of total dislikes on that comment
     */
    public function getCommentDislikesById($comment_id)
    {
        $this->db->query('SELECT COUNT(like_status) as dislikes FROM comments_like_dislike WHERE comment_id = :comment_id AND like_status = :like_status');
        $this->db->bind(':comment_id', $comment_id);
        $this->db->bind(':like_status', 0);
        return $this->db->single();
    }
}


?>