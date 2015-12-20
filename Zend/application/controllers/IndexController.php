<?php
class IndexController extends Zend_Controller_Action
{
 
    /**
	* Return current db adapter.
	*/
	protected function _getDbAdapter()
	{
		$front = Zend_Controller_Front::getInstance();
		$bootstrap = $front->getParam("bootstrap");
		$dbResource = $bootstrap->getPluginResource("db");
		return $dbResource->getDbAdapter();	
	}
 
    public function init()
    {	
    }	
 
    public function indexAction()
    {
		// Récupération de l'adapter à la base de données 
		$dbAdapter = $this->_getDbAdapter();
		
		// Récupération de l'ensemble des catégories de produits
		$sql = 'SELECT * FROM categorieproduit WHERE id_categorie >= 1 AND id_categorie <= 7';
		$categoriesProduits = $dbAdapter->fetchAll($sql);
		
		if (is_array($categoriesProduits))
		{
			foreach ($categoriesProduits as $categorieKey => $categorieValue)
			{				
				// Ajout du produit
				$tmpSql = 'SELECT * FROM produit WHERE id_categorie = ?';
				$tmpResult = $dbAdapter->fetchAll($tmpSql, $categorieValue['id_categorie']);
				$categoriesProduits[$categorieKey]['produits'] = $tmpResult;
			}
		}
		
		$sql = 'SELECT * FROM categorieproduit WHERE id_categorie = 8';
		$categoriesProduitsReco = $dbAdapter->fetchAll($sql);
		
		if (is_array($categoriesProduitsReco))
		{
			foreach ($categoriesProduitsReco as $categorieKey => $categorieValue)
			{				
				// Ajout du produit
				$tmpSql = 'SELECT * FROM produit WHERE id_categorie = ?';
				$tmpResult = $dbAdapter->fetchAll($tmpSql, $categorieValue['id_categorie']);
				$categoriesProduitsReco[$categorieKey]['produits'] = $tmpResult;
			}
		}
		
		// Assignation des valeurs à la vue
		$this->view->categoriesProduits = $categoriesProduits;
		$this->view->categoriesProduitsReco = $categoriesProduitsReco;
    }
}