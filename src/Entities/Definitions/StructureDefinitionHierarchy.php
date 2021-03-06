<?php

/**
 * \AppserverIo\Doppelgaenger\Entities\Definitions\StructureDefinitionHierarchy
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * PHP version 5
 *
 * @author    Bernhard Wick <bw@appserver.io>
 * @copyright 2015 TechDivision GmbH - <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io/doppelgaenger
 * @link      http://www.appserver.io/
 */

namespace AppserverIo\Doppelgaenger\Entities\Definitions;

use AppserverIo\Doppelgaenger\Interfaces\StructureDefinitionInterface;

/**
 * Keeps track of structure definitions which are directly or indirectly related to each other
 *
 * @author    Bernhard Wick <bw@appserver.io>
 * @copyright 2015 TechDivision GmbH - <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io/doppelgaenger
 * @link      http://www.appserver.io/
 */
class StructureDefinitionHierarchy
{
    /**
     * @var array $nodes The array holding the different structure definitions
     */
    protected $nodes = array();

    /**
     * Will insert a structure definition into our hierarchy
     *
     * @param \AppserverIo\Doppelgaenger\Interfaces\StructureDefinitionInterface $node The structure definition to insert
     *
     * @return bool
     */
    public function insert(StructureDefinitionInterface $node)
    {
        // Already here? Nothing to do then
        $qualifiedName = $node->getQualifiedName();
        if (!empty($this->nodes[$qualifiedName])) {
            return true;
        }

        // Add the node
        $this->nodes[$qualifiedName] = $node;

        // Add empty entries for the dependencies so we can check if all where added
        $dependencies = $node->getDependencies();
        foreach ($dependencies as $dependency) {
            if (!empty($this->nodes[$dependency])) {
                continue;

            } else {
                $this->nodes[$dependency] = null;
            }
        }

        // Still here? Sounds great
        return true;
    }

    /**
     * Will return an entry specified by it's name
     *
     * @param string $entryName Name of the entries we search for
     *
     * @return bool
     */
    public function getEntry($entryName)
    {
        if (!isset($this->nodes[$entryName]) || !is_null($this->nodes[$entryName])) {
            return false;
        }

        return $this->nodes[$entryName];
    }

    /**
     * Check if a certain entry exists
     *
     * @param string $entryName Name of the entries we search for
     *
     * @return bool
     */
    public function entryExists($entryName)
    {
        if (!isset($this->nodes[$entryName]) || !is_null($this->nodes[$entryName])) {
            return false;

        } else {
            return true;
        }
    }

    /**
     * Will return true if all possible node entries contain data
     *
     * @return bool
     */
    public function isComplete()
    {
        foreach ($this->nodes as $node) {
            if (is_null($node)) {
                return false;
            }
        }

        return true;
    }
}
