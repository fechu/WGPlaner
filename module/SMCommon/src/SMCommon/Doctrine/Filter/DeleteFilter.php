<?php
/**
 * @file DeleteFilter.php
 * @date Aug 7, 2013
 * @author Sandro Meier
 */

namespace SMCommon\Doctrine\Filter;

use Doctrine\ORM\Query\Filter\SQLFilter;
use Doctrine\ORM\Mapping\ClassMetadata;

/**
 * A filter that filters all value that have 'deleted' = 1.
 * Only not deleted entites will be shown
 */
class DeleteFilter extends SQLFilter
{
    public function addFilterConstraint(ClassMetadata $targetEntity, $targetTableAlias)
	{
		return $targetTableAlias . '.deleted = 0';
	}
}