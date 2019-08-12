<?php namespace Tatter\Thumbnails\Models;

use CodeIgniter\Model;

class ThumbnailModel extends Model
{
	protected $table      = 'thumbnails';
	protected $primaryKey = 'id';

	protected $returnType = 'object';
	protected $useSoftDeletes = true;

	protected $allowedFields = ['name', 'uid', 'class', 'summary', 'extensions'];

	protected $useTimestamps = true;

	protected $validationRules    = [];
	protected $validationMessages = [];
	protected $skipValidation     = false;

	// Retrieves a list of handlers that support a given extension
	public function getForExtension(string $extension)
	{
		$extension = $extension ?: '*';
		return $this->builder()
			->like('extensions', $extension, 'both')
			->get()->getResult($this->returnType);
	}
}
