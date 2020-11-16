<?php
    class Issue implements JsonSerializable {
        private $db;
        private $id;
        private $title;
        private $content;
        private $topic;
        private $warner;
        private $creator;
        private $author;
        private $receiver;
        private $kind;
        private $priority;
        private $creationDate;
        private $lastUpdate;
        private $status;
        private $billed;
        private $private;

        public function __construct($id) {
            $db = new Db();
            $issue = $db->row("SELECT * FROM issues WHERE issue_id = :id" , array("id" => $id));

            $this->db = $db;
            $this->id = $issue['issue_id'];
            $this->title = $issue['issue_title'];
            $this->content = $issue['issue_content'];
            $this->topic = $issue['issue_topic'];
            $this->warner = $issue['issue_warner'];
            $this->creator = $issue['creator_id'];
            $this->author = $issue['author_id'];
            $this->receiver = $issue['receiver_id'];
            $this->kind = $issue['issue_kind'];
            $this->priority = $issue['issue_priority'];
            $this->creationDate = $issue['issue_creation_date'];
            $this->lastUpdate = $issue['issue_last_update'];
            $this->status = $issue['issue_status'];
            $this->billed = $issue['issue_billed'];
            $this->private = $issue['issue_private'];
        }

        // Getters
        public function getId() {
            return $this->id;
        }

        public function getTitle() {
            return $this->title;
        }

        public function getContent() {
            return $this->content;
        }

        public function getTopic() {
            return $this->topic;
        }

        public function getWarner() {
            return $this->warner;
        }

        public function getCreator() {
            if($this->creator) {
                return new User($this->creator);
            } else {
                return null;
            }
        }

        public function getAuthor() {
            if($this->author) {
                return new User($this->author);
            } else {
                return null;
            }
        }

        public function getReceiver() {
            if($this->receiver) {
                return new User($this->receiver);
            } else {
                return null;
            }
        }

        public function getkind() {
            return $this->kind;
        }

        public function getPriority() {
            return $this->priority;
        }

        public function getCreationDate() {
            return $this->creationDate;
        }

        public function getLastUpdate() {
            return $this->lastUpdate;
        }

        public function getStatus() {
            return $this->status;
        }

        public function getBilled() {
            return $this->billed;
        }

        public function getPrivate() {
            return $this->private;
        }

        public function getAttachments() {
            return Attachment::listWithIssueId($this->id);
        }

        public function getComments() {
            return Comment::listWithIssueId($this->id);
        }
        // Methods

        public function jsonSerialize() {
            return array(
                "id" => $this->getId(),
                "title" => $this->getTitle(),
                "content" => $this->getContent(),
                "topic" => $this->getTopic(),
                "warner" => $this->getWarner(),
                "creator" => $this->getCreator(),
                "author" => $this->getAuthor(),
                "receiver" => $this->getReceiver(),
                "kind" => $this->getKind(),
                "priority" => $this->getPriority(),
                "creationDate" => $this->getCreationDate(),
                "lastUpdate" => $this->getLastUpdate(),
                "attachments" => $this->getAttachments(),
                "comments" => $this->getComments(),
                "status" => $this->getStatus(),
                "billed" => $this->getBilled(),
                "private" => $this->getPrivate(),
            );
        }

        // Static methods
        public static function list() {
            $db = new Db();

            $issues = array();

            $rows = $db->query("SELECT * FROM issues");

            foreach($rows as $row) {
                $issue = new Issue($row['issue_id']);

                $issues[] = $issue;
            }

            return $issues;
        }

        public static function listDateInterval($start, $end) {
            $db = new Db();

            $issues = array();

            $rows = $db->query("SELECT * FROM issues WHERE issue_creation_date > :start AND issue_creation_date < :end", 
                                array("start" => $start, "end" => $end,));

            foreach($rows as $row) {
                $issue = new Issue($row['issue_id']);

                $issues[] = $issue;
            }

            return $issues;
        }
        
        public static function warnersList() {
            $db = new Db();
            
            $warners = array();

            $rows = $db->query("SELECT issue_warner FROM issues WHERE issue_warner IS NOT NULL");

            foreach($rows as $row) {
                // Divido l'array trovato con delle virgole
                $parts = explode(',', $row["issue_warner"]);
                
                // Rimuovo gli spazi iniziali e finali
                $parts = array_map(function($item) {
                    return trim($item);
                }, $parts);
                
                // Unisco il nuovo array con quello finale
                $warners = array_merge($warners, $parts);
            }

            // Rimuovo i duplicati
            $warners = array_unique($warners);
            
            return $warners;
        }
        
        public static function addIssue($title, $content, $topic, $warner, $creator, $author, $receiver, $kind, $priority, $status, $creationDate, $lastUpdate, $private) {
            $db = new Db();

            $issue = $db->query("INSERT INTO issues (issue_title, issue_content, issue_topic, 
                                                     issue_warner, creator_id, author_id, receiver_id, 
                                                     issue_kind, issue_priority, issue_status, 
                                                     issue_creation_date, issue_last_update, issue_private) 
                                    VALUES (:title, :content, :topic, :warner, :creator, :author, :receiver, :kind, :priority, :status, :creationDate, :lastUpdate, :private)", array(
                                        "title" => $title,
                                        "content" => $content,
                                        "topic" => $topic,
                                        "warner" => $warner,
                                        "creator" => $creator,
                                        "author" => $author,
                                        "receiver" => $receiver,
                                        "kind" => $kind,
                                        "priority" => $priority,
                                        "status" => $status,
                                        "creationDate" => $creationDate,
                                        "lastUpdate" => $lastUpdate,
                                        "private" => $private,
                                    ));
            return new Issue($issue);
        }

        // Public methods
        public function deleteIssue() {
            $row = $this->db->query("DELETE FROM issues WHERE issue_id = :id", array(
                "id" => $this->id
            ));

            if($row > 0) {
                return true;
            } else {
                return false;
            }
        }

        public function editIssue($title, $content, $topic, $warner, $creator, $author, $receiver, $kind, $priority, $status, $billed, $private, $creationDate, $lastUpdate) {
            $row = $this->db->query("UPDATE issues SET  issue_title = :title, issue_content = :content, issue_topic = :topic, 
                                                        issue_warner = :warner, creator_id = :creator, author_id = :author, 
                                                        receiver_id = :receiver, issue_kind = :kind,
                                                        issue_status = :status, issue_billed = :billed, issue_private = :private,
                                                        issue_priority = :priority, issue_creation_date = :creationDate,
                                                        issue_last_update = :lastUpdate
                                     WHERE issue_id = :id", array(
                'id' => $this->id,
                'title' => $title,
                'content' => $content,
                'topic' => $topic,
                'warner' => $warner,
                'creator' => $creator,
                'author' => $author,
                'receiver' => $receiver,
                'kind' => $kind,
                'priority' => $priority,
                'status' => $status,
                'billed' => $billed,
                'private' => $private,
                'creationDate' => $creationDate,
                'lastUpdate' => $lastUpdate,
            ));
            
            if($row > 0) {
                return true;
            } else {
                return false;
            }
        }
    }