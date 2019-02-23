<?php

namespace App\Repositories;


interface DealerRepository
{
    /**
     * Get all the resource with pagination by searching having regex for string.
     *
     * *
     * @param $columns
     * @param $size
     * @return array(Resource)
     */
    public function regexSearch($columns, $size, $sorts = null);

    /**
     * Get all the resource with pagination by searching having columns.
     *
     * @param $columns
     * @param $size
     * @return array(Resource)
     */
    public function search($columns, $size);


    /**
     * Destroy all the resource having provided ids.
     *
     * @param array($ids)
     * @return array(Resource)
     */
    public function destroy($ids);

    /**
     * Create resource from the columns.
     *
     * @param $columns
     * @internal param $array ($columns)
     * @return array(Resource)
     */
    public function create($columns, $user = null);

    /**
     * Update resource from the columns.
     *
     * @param $resource
     * @param $columns
     * @internal param $array ($columns)
     * @return array(Resource)
     */
    public function update($resource, $columns);

    /**
     * Attach logo to resource
     *
     * @param $resource
     * @param $thumbnail
     * @return mixed
     */
    public function attachLogo($resource, $logo);

    /**
     * Attach logo to resource
     *
     * @param $resource
     * @param $adImage
     * @internal param $thumbnail
     * @return mixed
     */
    public function attachAdImage($resource, $adImage);

    /**
     * Find resource by id
     *
     * @param $id
     * @return mixed
     */
    public function find($id);
}