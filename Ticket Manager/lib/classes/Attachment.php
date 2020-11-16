<?php
    require_once __DIR__."/../config.php";

    class Attachment implements JsonSerializable{
        private $db;
        private $id;
        private $name;
        private $extension;
        private $size;
        private $issue;
        

        public function __construct($id) {
            $db = new Db();
            $attachment = $db->row("SELECT * FROM attachments WHERE attachment_id = :id" , array("id" => $id));

            $this->db = $db;
            $this->id = $attachment['attachment_id'];
            $this->name = $attachment['attachment_name'];
            $this->extension = $attachment['attachment_extension'];
            $this->size = $attachment['attachment_size'];
            $this->issue = $attachment['issue_id'];
        }

        // Getters
        public function getId() {
            return $this->id;
        }
        
        public function getName() {
            return $this->name;
        }
        public function getExtension() {
            return $this->extension;
        }
        public function getSize() {
            return $this->size;
        }

        // Methods

        public function jsonSerialize() {
            return array(
                "id" => $this->getId(),
                "name" => $this->getName(),
                "extension" => $this->getExtension(),
                "size" => $this->getSize(),
                "url" => BASE_URL."uploads/attachments/".$this->getId().".".$this->getExtension(), //http://localhost/Riccardo/Projects/Ticket%20Manager/uploads/attachments/5.txt
            );
        }

        public function getPath() {
            return ABSOLUTE_PATH.DIRECTORY_SEPARATOR."uploads".DIRECTORY_SEPARATOR."attachments".DIRECTORY_SEPARATOR.$this->getId().".".$this->getExtension();
        }
        public function getURL() {
            return BASE_URL."/uploads/attachments/".$this->getId().".".$this->getExtension();
        }
        // Static methods
        
        public static function list() {
            $db = new Db();

            $attachments = array();

            $rows = $db->query("SELECT * FROM attachments");

            foreach($rows as $row) {
                $attachment = new Attachment($row['attachment_id']);

                $attachments[] = $attachment;
            }

            return $attachments;
        }
        
        public static function listWithIssueId($issue) {
            $db = new Db();

            $attachments = array();

            $rows = $db->query("SELECT * FROM attachments WHERE issue_id = :issue", array('issue' => $issue));

            foreach($rows as $row) {
                $attachment = new Attachment($row['attachment_id']);

                $attachments[] = $attachment;
            }

            return $attachments;
        }

        public static function addAttachment($name, $extension, $size, $issue = NULL) {
            $db = new Db();

            $attachment = $db->query("INSERT INTO attachments (attachment_name, attachment_extension, attachment_size, issue_id) 
                                    VALUES (:name, :extension, :size, :issue)", array(
                                        "name" => $name,
                                        "extension" => $extension,
                                        "size" => $size,
                                        "issue" => $issue
                                    ));

            return new Attachment($attachment);
        }

        // Public methods

        public function deleteAttachment() {
            $row = $this->db->query("DELETE FROM attachments WHERE attachment_id = :id", array(
                "id" => $this->id
            ));
            
            if($row > 0) {
                unlink($this->getPath());
                return true;
            } else {
                return false;
            }
        }

        // Link Attachment to Issue
        public function linkToIssue($issueId) {
            $row = $this->db->query("UPDATE attachments SET issue_id = :issueId WHERE attachment_id = :id", array(
                'id' => $this->id,
                'issueId' => $issueId,
            ));
            
            if($row > 0) {
                return true;
            } else {
                return false;
            }
        }
    }