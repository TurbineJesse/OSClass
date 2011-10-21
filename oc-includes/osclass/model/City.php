<?php

    /*
     *      OSCLass – software for creating and publishing online classified
     *                           advertising platforms
     *
     *                        Copyright (C) 2010 OSCLASS
     *
     *       This program is free software: you can redistribute it and/or
     *     modify it under the terms of the GNU Affero General Public License
     *     as published by the Free Software Foundation, either version 3 of
     *            the License, or (at your option) any later version.
     *
     *     This program is distributed in the hope that it will be useful, but
     *         WITHOUT ANY WARRANTY; without even the implied warranty of
     *        MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
     *             GNU Affero General Public License for more details.
     *
     *      You should have received a copy of the GNU Affero General Public
     * License along with this program.  If not, see <http://www.gnu.org/licenses/>.
     */

    /**
     * Model database for City table
     * 
     * @package OSClass
     * @subpackage Model
     * @since unknown
     */
    class City extends DAO
    {
        /**
         * It references to self object: City.
         * It is used as a singleton
         * 
         * @access private
         * @since unknown
         * @var City 
         */
        private static $instance ;

        /**
         * It creates a new City object class ir if it has been created
         * before, it return the previous object
         * 
         * @access public
         * @since unknown
         * @return City 
         */
        public static function newInstance()
        {
            if( !self::$instance instanceof self ) {
                self::$instance = new self ;
            }
            return self::$instance ;
        }

        /**
         * Set data related to t_city table
         */
        function __construct()
        {
            parent::__construct();
            $this->setTableName('t_city') ;
            $this->setPrimaryKey('pk_i_id') ;
            $this->setFields( array('pk_i_id', 'fk_i_region_id', 's_name', 'fk_c_country_code', 'b_active') ) ;
        }

        /**
         * Get the cities having part of the city name and region (it can be null)
         * 
         * @access public
         * @since unknown
         * @param string $query The beginning of the city name to look for
         * @param int|null $regionId Region id
         * @return array If there's an error or 0 results, it returns an empty array
         */
        function ajax($query, $regionId = null)
        {
            $this->dao->select('pk_i_id as id, s_name as label, s_name as value') ;
            $this->dao->from($this->getTableName()) ;
            $this->dao->like('s_name', $query, 'after') ;
            if( $regionId != null ) {
                $this->dao->where('fk_i_region_id', $regionId) ;
            }

            $result = $this->dao->get() ;

            if( $result == false ) {
                return array() ;
            }

            return $result->result() ;
        }

        /**
         * Get the cities from an specific region id. It's deprecated, use findByRegion
         * 
         * @access public
         * @since unknown
         * @deprecated deprecated since 2.3
         * @see City::findByRegion
         * @param int $regionId Region id
         * @return array If there's an error or 0 results, it returns an empty array
         */
        function getByRegion($regionId)
        {
            return $this->findByRegion($regionId) ;
        }

        /**
         * Get the cities from an specific region id
         * 
         * @access public
         * @since 2.3
         * @param int $regionId Region id
         * @return array If there's an error or 0 results, it returns an empty array
         */
        function findByRegion($regionId)
        {
            $this->dao->select($this->getFields()) ;
            $this->dao->from($this->getTableName()) ;
            $this->dao->where('fk_i_region_id', $regionId) ;
            $this->dao->orderBy('s_name', 'ASC') ;

            $result = $this->dao->get() ;

            if( $result == false ) {
                return array() ;
            }

            return $result->result() ;
        }

        /**
         * Get the citiy by its name and region
         * 
         * @access public
         * @since unknown
         * @param string $query
         * @param int $regionId
         * @return array 
         */
        function findByName($cityName, $regionId = null)
        {
            $this->dao->select($this->getFields()) ;
            $this->dao->from($this->getTableName()) ;
            $this->dao->where('s_name', $cityName) ;
            $this->dao->limit(1) ;
            if( $regionId != null ) {
                $this->dao->where('fk_i_region_id', $regionId) ;
            }

            $result = $this->dao->get() ;

            if( $result == false ) {
                return array() ;
            }

            return $result->row() ;
        }


    }

    /* file end: ./oc-includes/osclass/model/new_model/City.php */
?>