<?php

namespace common\components\tools\sendcloud\lib\util;
/**
 * 
 * @author xjm
 *
 */
class Attachment implements \JsonSerializable {
	private $content, $type, $filename, $disposition, $content_id;
	public function setContent($content) {
		$this->content = $content;
	}
	public function getContent() {
		return $this->content;
	}
	public function setType($type) {
		$this->type = $type;
	}
	public function getType() {
		return $this->type;
	}
	public function setFilename($filename) {
		$this->filename = $filename;
	}
	public function getFilename() {
		return $this->filename;
	}
	public function setDisposition($disposition) {
		$this->disposition = $disposition;
	}
	public function getDisposition() {
		return $this->disposition;
	}
	public function setContentID($content_id) {
		$this->content_id = $content_id;
	}
	public function getContentID() {
		return $this->content_id;
	}
	public function jsonSerialize() {
		return array_filter ( [ 
				'content' => $this->getContent (),
				'type' => $this->getType (),
				'filename' => $this->getFilename (),
				'disposition' => $this->getDisposition (),
				'content_id' => $this->getContentID () 
		] );
	}
}
