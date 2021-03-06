<?php
class Doc extends sModel {
    /**
     * Add a new piece of documentation
     * @param string $methodName
     * @param string $comments
     * @param string $example
     * @param string $file
     * @return boolean
     */
	function create($methodName, $comments, $example, $file){
		$q = new sQuery();
		return $q->table('docs')
				->set('method_name', $methodName)
				->set('comments', $comments)
				->set('example', $example)
				->set('file', $file)
				->insert();
//		$q->addField('method_name', $methodName);
//		$q->addField('comments', $comments);
//		$q->addField('example', $example);
//		$q->addField('file', $file);
//		return $q->insert();
	}
    /**
     * Get the documentation for a specific file
     * @param string $fileName
     * @return array
     */
	function getDocs($fileName){
		$q = new sQuery();
		return $q->from('docs')
				->where('file', $fileName)
				->selectAll();
	}
    /**
     * Get a specific doc
     * @param int $id
     * @return array
     */
	function getDoc($id){
		$q = new sQuery();
		return $q->from('docs')
				->where('id', $id)
				->selectRow();
	}
    /**
     * Get all of the files that are documented
     * @return array
     */
	function getFiles(){
		$q = new sQuery();
		$result = $q->from('docs')
				->column('file')
				->orderBy('file', 'ASC')
				->groupBy('file')
				->selectAll();
		foreach($result as $res){
			$return[] = $res['file'];
		}
		return $return;
	}
    /**
     * Update a specific doc
     * @param int $id
     * @param string $methodName
     * @param string $comments
     * @param string $example
     * @param string $file
     * @return boolean
     */
	function update($id, $methodName, $comments, $example, $file){
		$q = new sQuery();
        $result = $q->into('docs')
            ->where('id', $id)
            ->set('method_name', $methodName)
            ->set('comments', $comments)
            ->set('example', $example)
            ->set('file', $file)
            ->update();
        if(!$result){
            return $q->getError();
        }
//		$q->setInto('docs');
//		$q->addWhere('id', $id);
//		$q->addField('method_name', $methodName);
//		$q->addField('comments', $comments);
//		$q->addField('example', $example);
//		$q->addField('file', $file);
//		if(!$q->update()){
//			return $q->getError();
//		}
	}
    /**
     * Delete a specific doc
     * @param int $id
     * @return boolean
     */
	function delete($id){
		$q = new sQuery();
        return $q->from('docs')
            ->where('id', $id)
            ->delete();
//		$q->setFrom('docs');
//		$q->addWhere('id', $id);
//		return $q->delete();
	}
}
