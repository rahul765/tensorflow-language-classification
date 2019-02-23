<?php

namespace App\Repositories\Eloquent;

use App\Brand;
use App\Dealer;
use App\Picture;
use App\Repositories\BaseRepository;
use App\Repositories\BrandRepository;
use App\Repositories\DealerRepository;
use App\Repositories\UserRepository;
use Validator;

class EloquentDealerRepository extends BaseRepository implements DealerRepository {

    protected $model;

    function __construct(Dealer $model) {
        $this->model = $model;
    }

    public function create($columns, $user = null)
    {
        if(isset($user)) {
            unset($columns['user_id']);
            $columns['user_id'] = $user->id;
        }
        return parent::create($columns);
    }

    public function attachLogo($dealer, $logo) {
        if (isset($logo) && $logo->isValid()) {
            $basePath = 'uploads/';
            $destinationPath = 'dealers'; // upload path
            $extension = $logo->getClientOriginalExtension(); // getting image extension
            $fileName = 'dealer-logo-' . str_pad($dealer->id, 10, '0', STR_PAD_LEFT) . '.' . $extension; // renameing image
            $logo->move($basePath . $destinationPath, $fileName); // uploading file to given path

            $dealer->logo = "$destinationPath/$fileName";
            $dealer->save();
        }
        return $dealer;
    }

    /**
     * Attach logo to resource
     *
     * @param $resource
     * @param $adImage
     * @internal param $thumbnail
     * @return mixed
     */
    public function attachAdImage($dealer, $adImage)
    {
        if (isset($adImage) && $adImage->isValid()) {
            $basePath = 'uploads/';
            $destinationPath = 'dealers'; // upload path
            $extension = $adImage->getClientOriginalExtension(); // getting image extension
            $fileName = 'dealer-ad-' . str_pad($dealer->id, 10, '0', STR_PAD_LEFT) . '.' . $extension; // renameing image
            $adImage->move($basePath . $destinationPath, $fileName); // uploading file to given path

            $dealer->ad_image = "$destinationPath/$fileName";
            $dealer->save();
        }
        return $dealer;
    }

    /**
     * Attach galary pictures to resources
     *
     * @param $resource
     * @param $pictures
     * @return mixed
     */
    public function attachPictures($dealer, $pictures)
    {
        $counter = $dealer->pictures()?$dealer->pictures()->count():0;
        if(is_array($pictures)) {
            foreach($pictures as $picture) {
                if (isset($picture) && $picture->isValid()) {
                    $basePath = 'uploads/';
                    $destinationPath = 'dealers'; // upload path
                    $extension = $picture->getClientOriginalExtension(); // getting image extension
                    $fileName = 'dealer-galary-'. $dealer->id . '-' . milliseconds() . (++$counter) . '.' . $extension;
                    $picture->move($basePath . $destinationPath, $fileName); // uploading file to given path

                    $pictureEntity = new Picture();
                    $pictureEntity->path = "$destinationPath/$fileName";
                    $pictureEntity->caption = $dealer->name;
                    $dealer->pictures()->save($pictureEntity);
                }
            }
        }
        return $dealer;
    }
}