<?php

namespace App\Repositories;


interface VehicleRepository
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
     * Get all the resource with pagination by searching having regex for string.
     *
     * *
     * @param $columns
     * @param $size
     * @return array(Resource)
     */
    public function regexSearchFrontend($columns, $size, $sorts = null);

    /**
     * Get all the resource with pagination by searching having regex for string.
     *
     * *
     * @param $columns
     * @param $size
     * @return array(Resource)
     */
    public function searchFrontend($columns, $size, $sorts = null);

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
    public function create($columns);

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
    public function attachThumbnail($resource, $thumbnail);

    /**
     * Attach brochure to resource
     *
     * @param $resource
     * @param $brochure
     * @return mixed
     */
    public function attachBrochure($resource, $brochure);

    /**
     * Attach galary pictures to resources
     *
     * @param $resource
     * @param $pictures
     * @return mixed
     */
    public function attachPictures($resource, $pictures);

    /**
     * Find the vehicle by brand, model and variant
     *
     * @param $brand
     * @param $model
     * @param $variant
     * @param bool $is_brand_id
     * @return mixed
     */
    public function findByBrandModelVariant($brand, $model, $variant, $is_brand_id = true);

    /**
     * Find the vehicle by id
     * @param $id
     * @return Vehicle
     */
    public function find($id);

    /**
     * @param $value
     * @param $key
     * @param null $columns
     * @return mixed
     */
    public function lists($value, $key, $columns = null);
}