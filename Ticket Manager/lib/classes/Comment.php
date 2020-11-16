<?php
    class Comment implements JsonSerializable {
        private $db;
        private $id;
        private $content;
        private $date;
        private $author;
        private $issue;

        public function __construct($id) {
            $db = new Db();
            $comment = $db->row("SELECT * FROM comments WHERE comment_id = :id" , array("id" => $id));

            $this->db = $db;
            $this->id = $comment['comment_id'];
            $this->content = $comment['comment_content'];
            $this->date = $comment['comment_date'];
            $this->author = $comment['author_id'];
            $this->issue = $comment['issue_id'];
        }

        // Getters
        
        public function getId() {
            return $this->id;
        }

        public function getContent() {
            return $this->content;
        }

        public function getDate() {
            return $this->date;
        }

        public function getAuthor() {
            if($this->author) {
                return new User($this->author);
            } else {
                return null;
            }
        }

        public function getIssue() {
            if($this->issue) {
                return new Issue($this->issue);
            } else {
                return null;
            }
        }

        // Methods

        public function jsonSerialize() {
            return array(
                "id" => $this->getId(),
                "content" => $this->getContent(),
                "date" => $this->getDate(),
                "author" => $this->getAuthor(),
                "issue" => $this->issue
            );
        }

        // Static methods
        public static function list() {
            $db = new Db();

            $comments = array();

            $rows = $db->query("SELECT * FROM comments");

            foreach($rows as $row) {
                $comment = new Comment($row['comment_id']);

                $comments[] = $comment;
            }

            return $comments;
        }

        public static function addComment($content, $date, $author, $issue) {
            $db = new Db();

            $comment = $db->query("INSERT INTO comments (comment_content, comment_date, author_id, issue_id) 
                                    VALUES (:content, :date, :author, :issue)", array(
                                        "content" => $content,
                                        "date" => $date,
                                        "author" => $author,
                                        "issue" => $issue,
                                    ));
            return new Comment($comment);
        }

        public static function listWithIssueId($issue) {
            $db = new Db();

            $comments = array();

            $rows = $db->query("SELECT * FROM comments WHERE issue_id = :issue", array('issue' => $issue));

            foreach($rows as $row) {
                $comment = new Comment($row['comment_id']);

                $comments[] = $comment;
            }

            return $comments;
        }

        public static function listWithUserId($user) {
            $db = new Db();

            $comments = array();

            $rows = $db->query("SELECT * FROM comments WHERE user_id = :user", array('user' => $user));

            foreach($rows as $row) {
                $comment = new Comment($row['comment_id']);

                $comments[] = $comment;
            }

            return $comments;
        }

        // Public methods
        public function deleteComment() {
            $row = $this->db->query("DELETE FROM comments WHERE comment_id = :id", array(
                "id" => $this->id
            ));

            if($row > 0) {
                return true;
            } else {
                return false;
            }
        }

        public function editComment($content, $date, $issue, $author) {
            $row = $this->db->query("UPDATE comments SET  comment_content = :content, comment_date = :date, 
                                                        issue_id = :issue, author_id = :author
                                     WHERE comment_id = :id", array(
                'id' => $this->id,
                'content' => $content,
                'date' => $date,
                'issue' => $issue,
                'author' => $author,
            ));
            
            if($row > 0) {
                return true;
            } else {
                return false;
            }
        }
    }