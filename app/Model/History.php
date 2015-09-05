<?php
App::uses('CakeEvent', 'Event');

class History extends AppModel {

	public function afterSave($created, $options = array()) {
		if($created) {
			// nouvel enregistrement
			
			switch ($this->data['History']['action']) {
				case 'BUY_ITEM':
					$author = $this->data['History']['author'];
					$item_name = $this->data['History']['other'];
					$informations = array('buyer' => $author, 'item_name' => $item_name);
					$this->getEventManager()->dispatch(new CakeEvent('afterBuy', $informations));
					break;
				case 'BUY_MONEY':
					$this->getEventManager()->dispatch(new CakeEvent('afterAddMoney', $this));
					break;
				
				default:
					$this->getEventManager()->dispatch(new CakeEvent('afterAddHistory', $this));
					break;
			}
		}
	}

	public function afterDelete() {
		$this->getEventManager()->dispatch(new CakeEvent('afterDeleteHistory', $this));
	}

}