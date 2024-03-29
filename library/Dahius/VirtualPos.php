<?php
/**
 * Virtual Pos Library
 *
 * Copyright (c) 2008-2009 Dahius Corporation (http://www.dahius.com)
 * All rights reserved.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *  
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the LGPL.
 */

/**
 * @package     VirtualPos
 * @author      Hasan Ozgan <hasan@dahius.com>
 * @copyright   2008-2009 Dahius Corporation (http://www.dahius.com)
 * @license     http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License
 * @version     $Id$
 * @link        http://vpos4php.googlecode.com
 * @since       0.1
 */

class Dahius_VirtualPos
{
    protected $_adapters;

    public function __construct($config)
    {
        if (is_array($config)){
            $this->_adapters = $config;
        } else {
            $file = new Joy_File($config);
            $this->_adapters = $file->getReader()->toArray();
        }
    }

    /**
     * factory method is adapter factory  
     *
     * @param string $adapterName 
     * @return Dahius_VirtualPos_Interface 
     */
    public function factory($adapterName)
    {
        if (empty($adapterName)) {
            throw new Dahius_VirtualPos_Exception("AdapterName not set");
        }
        
        if (!array_key_exists($adapterName, $this->_adapters)) {
            throw new Dahius_VirtualPos_Exception("AdapterName($adapterName) not found");
        }

        $adapter = $this->_adapters[$adapterName]["adapter"];
        $params = $this->_adapters[$adapterName]["parameters"];

        $ref = new Joy_Reflection($adapter);
        return $ref->newInstance(array($adapterName, $params));
    }
}
